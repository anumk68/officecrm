<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHrRequestRequest;
use App\Models\HrRequest;
use App\Models\User;
use App\Notifications\HrRequestCreated;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class HrRequestController extends Controller
{

    use NotifiesUsers;
    // Employee: create form
    public function create()
    {
        return view('hr_requests.create');
    }

    // Employee: store request
    public function store(StoreHrRequestRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle attachment upload
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('hr_attachments', 'public');
                $data['attachment'] = $path;
            }

            $data['user_id'] = Auth::id();
            $hrRequest = HrRequest::create($data);
            $this->notifyOfRequestCreate(auth()->user(), $hrRequest->type ?? 'General');

            return redirect()->route('hr.requests.my')->with('success', 'Request submitted successfully.');
        } catch (\Exception $e) {
            Log::error('HR Request store error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to submit request.');
        }
    }


    // Employee: list own requests
    public function myRequests()
    {
        $requests = HrRequest::where('user_id', Auth::id())->latest()->get();
        return view('hr_requests.my_requests', compact('requests'));
    }

    // HR: index all requests
    public function index()
    {
        // $this->authorize('viewAny', HrRequest::class); 
        $requests = HrRequest::with('user')->latest()->get();
        return view('hr_requests.index', compact('requests'));
    }

    // show detail
    public function show($id)
    {
        $request = HrRequest::with('user', 'assignedTo')->findOrFail($id);
        return view('hr_requests.show', compact('request'));
    }

    // HR: assign / respond / change status
    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:pending,in_review,resolved,rejected',
            'hr_response' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        try {
            $hrRequest = HrRequest::findOrFail($id);

            $hrRequest->status = $request->status;
            $hrRequest->hr_response = $request->hr_response ?? $hrRequest->hr_response;

            if ($request->filled('assigned_to')) {
                $hrRequest->assigned_to = $request->assigned_to;
            }

            $hrRequest->save();
            $this->notifyUserOfRequestUpdate(auth()->user(), $hrRequest);

            return back()->with('success', 'Request updated successfully.');
        } catch (\Exception $e) {
            Log::error('HR Request update error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while updating the request.');
        }
    }


    // Employee: delete own request (optional)
    public function destroy($id)
    {
        $hrRequest = HrRequest::findOrFail($id);
        if ($hrRequest->user_id != Auth::id()) {
            abort(403);
        }
        if ($hrRequest->attachment) {
            Storage::delete($hrRequest->attachment);
        }
        $hrRequest->delete();
        return back()->with('success', 'Request deleted.');
    }


public function dailyAttendance(Request $request)
{
    $year = $request->year ?? date('Y');
    $month = $request->month ?? date('m');

    // total active employees
    $totalEmployees = DB::table('users')
        ->where('status', 'active')
      
        ->whereIn('role',['team_member','team_leader', 'hr'] )
        ->count();

    // total days in month
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $dailyData = [];

    for ($day = 1; $day <= $daysInMonth; $day++) {

        $date = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($day, 2, '0', STR_PAD_LEFT);

        // Count DISTINCT users present on this date
        $presentCount = DB::table('attendances')
            ->whereDate('created_at', $date)
            ->distinct('user_id')
            ->count('user_id');

        $dailyData[] = $presentCount;
    }

    return response()->json([
        'year' => $year,
        'month' => $month,
        'days' => range(1, $daysInMonth),
        'data' => $dailyData,
        'totalEmployees' => $totalEmployees  // ✅ send to frontend
    ]);
}


}