<?php

namespace App\Http\Controllers;

use App\Models\Information;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformationController extends Controller
{
    use NotifiesUsers;
    /**
     * Show all informations
     */
    public function index()
    {
        if (Auth::user()->role == 'team_member' || Auth::user()->role == 'team_leader') {
            $informations = Information::where('status', 'active')->latest()->get();
        } else {
            $informations = Information::latest()->get();
        }
        return view('informations.index', compact('informations'));
    }

    /**
     * Show form to create new info
     */
    public function create()
    {
        return view('informations.create');
    }

    /**
     * Store new information
     */
    public function store(Request $request)
    {
      
            $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|in:announcement,policy,event,holiday,general',
                'attachment' => 'nullable|file|max:2048',
                'information_date' => 'required',
            ]);
              try {

            $data = $request->all();
            $data['created_by'] = Auth::id();

            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('informations', 'public');
            }

            $Information = Information::create($data);
            $this->notifyOfNewInformation($Information);

            return redirect()->route('informations.index')->with('success', 'Information created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create information: ' . $e->getMessage());
        }
    }

    /**
     * Edit form
     */
    public function edit(Information $information)
    {
        return view('informations.edit', compact('information'));
    }

    /**
     * Update info
     */
    public function update(Request $request, Information $information)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|in:announcement,policy,event,holiday,general',
                'attachment' => 'nullable|file|max:2048',
            ]);

            $data = $request->all();

            if ($request->hasFile('attachment')) {
                $data['attachment'] = $request->file('attachment')->store('informations', 'public');
            }

            $information->update($data);

            return redirect()->route('informations.index')->with('success', 'Information updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update information: ' . $e->getMessage());
        }
    }

    /**
     * Delete info
     */
    public function destroy(Information $information)
    {
        try {
            $information->delete();
            return redirect()->route('informations.index')->with('success', 'Information deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete information: ' . $e->getMessage());
        }
    }
    public function informationsbulkDelete(Request $request)
    {
        Information::whereIn('id', $request->ids)->delete();
        return redirect()->back()->with('success', 'Selected information deleted successfully!');
    }

}
