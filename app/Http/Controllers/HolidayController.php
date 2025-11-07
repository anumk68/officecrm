<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class HolidayController extends Controller
{
    use NotifiesUsers;
    //
    public function index()
    {
        try {
            if (Auth::user()->role == 'team_member' || Auth::user()->role == 'team_leader') {
                $holiday = Holiday::where('status', 'active')->latest()->get();
            } else {

                $holiday = Holiday::latest()->get();
            }
            return view('holiday.index', compact('holiday'));
        } catch (\Exception $e) {
            Log::error('Error fetching holiday: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while fetching holiday.' . $e->getMessage());
        }
    }
    public function create()
    {
        try {
            return view('holiday.create');
        } catch (\Exception $e) {
            Log::error('Error opening create lead form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'holiday_date' => 'required|date|unique:holidays,holiday_date',
            'type' => 'required|in:public,company',
            'status' => 'required',
        ]);

        try {
            $holiday = Holiday::create($request->all());

            // ✅ Send notification to all users
            $this->notifyOfNewHoliday($holiday);

            return redirect()->route('holiday.index')->with('success', 'Holiday created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating Holiday: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create Holiday.' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            $holiday = Holiday::find($id);
            return view('holiday.edit', compact('holiday'));
        } catch (\Exception $e) {
            Log::error('Error opening edit holiday form: ' . $e->getMessage());
            return redirect()->route('holiday.index')->with('error', 'Holiday not found.');
        }
    }
    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'holiday_date' => [
                'required',
                'date',
                Rule::unique('holidays', 'holiday_date')->ignore($holiday->id),
            ],
            'type' => 'required|in:public,company',
            'status' => 'required',
        ]);
        try {
            $holiday->update($request->all());

            return redirect()->route('holiday.index')->with('success', 'Holiday updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating holiday: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update holiday.');
        }
    }
    public function destroy(Holiday $holiday)
    {
        try {
            $holiday->delete();
            return redirect()->back()->with('success', 'holiday deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting holiday: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete holiday.');
        }
    }
    public function holidaybulkDelete(Request $request)
    {
        Holiday::whereIn('id', $request->ids)->delete();
        return redirect()->back()->with('success', 'Selected holidays deleted successfully!');
    }

}
