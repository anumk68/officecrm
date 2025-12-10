<?php
namespace App\Http\Controllers;

use App\Models\Activity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function index()
    {
        try {
            $activities = Activity::with(['lead', 'participant'])
                ->orderBy('id', 'desc')
                ->get();

            return view('lead.activity.index', compact('activities'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|exists:leads,id',
            'title' => 'required|string|max:255',
            'schedule_from' => 'required|date',
            'schedule_to' => 'required|date|after_or_equal:schedule_from',
            'activity_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            Activity::create([
                'lead_id' => $request->lead_id,
                'title' => $request->title,
                'description' => $request->description,
                'participant_id' => $request->participant_id,
                'schedule_from' => $request->schedule_from,
                'schedule_to' => $request->schedule_to,
                'location' => $request->location,
                'activity_type' => $request->activity_type,
            ]);

            return response()->json(['success' => 'Activity created successfully!']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $activity = Activity::findOrFail($id);
            $activity->delete();

            return redirect()->back()->with('success', 'Activity deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete activity: ' . $e->getMessage());
        }
    }

    public function toggleDone($id)
    {
        try {
            $activity = Activity::findOrFail($id);
            $activity->is_done = !$activity->is_done;
            $activity->save();

            return response()->json([
                'success' => true,
                'is_done' => $activity->is_done,
                'label' => $activity->is_done ? 'Done' : 'Pending',
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update status: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $activity = Activity::with('participant')->findOrFail($id);
            return view('lead.activity.edit', compact('activity'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load activity: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'schedule_from' => 'required|date',
            'schedule_to' => 'required|date|after_or_equal:schedule_from',
            'activity_type' => 'required',
        ]);
        try {
            $activity = Activity::findOrFail($id);
            $activity->update([
                'title' => $request->title,
                'description' => $request->description,
                'schedule_from' => $request->schedule_from,
                'schedule_to' => $request->schedule_to,
                'location' => $request->location,
                'activity_type' => $request->activity_type,
            ]);

            return redirect()->route('activity.index')->with('success', 'Activity updated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to update activity: ' . $e->getMessage());
        }
    }
    public function activitybulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return back()->with('toast_error', 'No activities selected!');
        }
        try {
            Activity::whereIn('id', $ids)->delete();
            return back()->with('toast_success', count($ids) . ' activit' . (count($ids) > 1 ? 'ies' : 'y') . ' deleted!');
        } catch (\Exception $e) {
            Log::error('Bulk Activity Delete Error: ' . $e->getMessage(), ['ids' => $ids]);
            return back()->with('toast_error', 'Failed to delete activities.');
        }
    }
}
