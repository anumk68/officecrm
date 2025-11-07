<?php

namespace App\Http\Controllers;

use App\Models\ContactPersons;
use App\Models\Lead;
use App\Models\LeadProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function index()
    {
        try {
            $leads = Lead::with(['contactPerson', 'leadProduct'])->latest()->get();
            return view('lead.leads.list', compact('leads'));
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
            return view('lead.leads.create', compact('contacts', 'products'));
        } catch (\Exception $e) {
            Log::error('Error opening create lead form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function store(Request $request)
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
            Lead::create($request->all());

            return redirect()->route('leads.index')->with('success', 'Lead created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating lead: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create lead.');
        }
    }

    public function edit($id)
    {
        try {
            $lead = Lead::findOrFail($id);
            $contacts = ContactPersons::all();
            $products = LeadProduct::all();
            return view('lead.leads.edit', compact('lead', 'contacts', 'products'));
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
            $lead = Lead::with(['contactPerson', 'leadProduct'])->findOrFail($id);
            $participants = ContactPersons::get();
            return view('lead.leads.show', compact('lead', 'participants'));
        } catch (\Exception $e) {
            Log::error('Error showing lead: ' . $e->getMessage());
            return redirect()->route('leads.index')->with('error', 'Lead not found.');
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


}
