<?php

namespace App\Http\Controllers;

use App\Models\ContactPersons;
use App\Models\Lead;
use App\Models\LeadApproval;
use App\Models\LeadProduct;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get filters
            $selectedColor = $request->color;
            $dateFrom = $request->date_from;
            $dateTo = $request->date_to;

            $user = auth()->user();
            // Query leads with dynamic filters
            $leads = Lead::when($selectedColor, function ($query) use ($selectedColor) {
                return $query->where('color', $selectedColor);
            })
                ->when($dateFrom, function ($query) use ($dateFrom) {
                    return $query->whereDate('created_at', '>=', $dateFrom);
                })
                ->when($dateTo, function ($query) use ($dateTo) {
                    return $query->whereDate('created_at', '<=', $dateTo);
                })
                ->when(in_array($user->role, ['sales', 'team_leader']), function ($query) use ($user) {
                    return $query->where('created_by', $user->id);
                })

                // Manager → Only converted leads (green)
                ->when($user->role === 'manager', function ($query) {
                    return $query->where('status', 'converted');
                })
                ->latest()
                ->get();

            return view('lead_old.leads.list', compact('leads', 'selectedColor'));
        } catch (\Exception $e) {
            Log::error('Error fetching leads: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while fetching leads.');
        }
    }

    public function create()
    {
        try {
            $contacts = ContactPersons::all();
            $products = LeadProduct::all();
            return view('lead_old.leads.create', compact('contacts', 'products'));
        } catch (\Exception $e) {
            Log::error('Error opening create lead form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'nullable|string',
            'platform' => 'nullable|string|in:ig,fb,yt,gm,wp',
            'additional_fields.*.label' => 'nullable|string',
            'additional_fields.*.value' => 'nullable|string',
        ]);
        $additionalData = [];
        if ($request->has('additional_fields')) {
            foreach ($request->input('additional_fields') as $field) {
                if (!empty($field['label']) && !empty($field['value'])) {
                    $label = trim($field['label']);
                    $value = trim($field['value']);
                    if (!empty($label) && !empty($value)) {
                        $additionalData[$label] = $value;
                    }
                }
            }
        }
        $lead = Lead::create([
            'full_name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'city' => $request->city,
            'platform' => $request->platform,
            'meta' => !empty($additionalData) ? json_encode($additionalData) : null,
            'created_by' => auth()->id(),
              'lead_source_id' => 4,
        ]);
        return redirect()->route('leads.index')->with('success', 'Lead created successfully!');
    }

    public function edit($id)
    {
        try {
            $lead = Lead::findOrFail($id);
            $contacts = ContactPersons::all();
            $products = LeadProduct::all();
            return view('lead_old.leads.edit', compact('lead', 'contacts', 'products'));
        } catch (\Exception $e) {
            Log::error('Error opening edit lead form: ' . $e->getMessage());
            return redirect()->route('leads.index')->with('error', 'Lead not found.');
        }
    }

    public function update(Request $request, Lead $lead)
    {

        $request->validate([
            'contact_person_id' => 'required|exists:contact_persons,id',
            'lead_product_id' => 'required|exists:lead_products,id',
            'lead_title' => 'required|string|max:255',
            'status' => 'required',
            'lead_value' => 'nullable|numeric',
            'source' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        try {
            $lead->update($request->all());

            return redirect()->route('leads.index')->with('success', 'Lead updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating lead: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update lead.');
        }
    }

    public function destroy(Lead $lead)
    {
        try {
            $lead->delete();
            return redirect()->back()->with('success', 'Lead deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting lead: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete lead.');
        }
    }

    public function show($id)
    {
        try {
            $lead = Lead::findOrFail($id);
            $participants = ContactPersons::get();
            return view('lead_old.leads.show', compact('lead', 'participants'));
        } catch (\Exception $e) {
            Log::error('Error showing lead: ' . $e->getMessage());
            return redirect()->route('leads.index')->with('error', 'Lead not found.' . $e->getMessage());
        }
    }
    public function leadsbulkDelete(Request $request)
    {
        try {
            $ids = $request->ids;
            if (empty($ids) || !is_array($ids)) {
                return back()->with('error', 'No leads selected!');
            }
            Lead::whereIn('id', $ids)->delete();
            return back()->with('success', 'Selected leads deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Bulk Lead Delete Error: ' . $e->getMessage(), [
                'ids' => $request->ids ?? null
            ]);
            return back()->with('error', 'Something went wrong while deleting leads. Please try again.');
        }
    }


    public function updateStatus(Request $request)
    {
        $request->validate([
            'lead_id' => 'required',
            'status' => 'required'
        ]);

        $lead = Lead::findOrFail($request->lead_id);

        // Only sales or TL allowed
        if (!in_array(auth()->user()->role, ['sales', 'team_leader'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $lead->color = $request->status;

        // If converted then update status also
        if ($request->status === 'green') {
            $lead->status = 'converted';
        }
        // Add pending approval entry
        if ($request->status === 'green') {
            LeadApproval::create([
                'lead_id' => $lead->id,
                'status' => 'pending'
            ]);
        }
        $lead->save();

        return response()->json(['message' => 'Lead status updated successfully!']);
    }

    // SHOW RECYCLE BIN
    public function recycleBin()
    {
        $leads = Lead::onlyTrashed()->where('created_by', auth()->user()->id)->latest()->get();
        return view('lead_old.leads.recycle_bin', compact('leads'));
    }

    // RESTORE LEAD
    public function restore($id)
    {
        Lead::onlyTrashed()->where('id', $id)->restore();

        return redirect()->back()->with('success', 'Lead restored successfully!');
    }

    // PERMANENT DELETE
    public function forceDelete($id)
    {
        Lead::onlyTrashed()->where('id', $id)->forceDelete();

        return redirect()->back()->with('success', 'Lead permanently deleted!');
    }
}
