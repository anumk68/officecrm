<?php

namespace App\Http\Controllers;

use App\Events\LeaveApplied;
use App\Models\Leave;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    use NotifiesUsers;
    // Show all leaves based on role
    public function index()
    {
        
        try {
            $user = Auth::user();
            $query = Leave::with('user')->latest();

            if ($user->role === 'hr') {
                $leaves = $query->where('team_leader_status', 'accepted')->get();
            } elseif ($user->role === 'manager') {
                $leaves = $query->where('team_leader_status', 'accepted')->get();
            } elseif ($user->role === 'team_leader') {
                $leaves = $query
                    ->whereHas('user', function ($q) use ($user) {
                        $q->where('department', $user->department);
                    })
                    ->get();
            } elseif ($user->role === 'team_member') {
                $leaves = $query
                    ->where('user_id', $user->id)
                    ->get();
            } else {
                $leaves = collect();
            }

            return view('leave.index', compact('leaves'));
        } catch (\Exception $e) {
            Log::error('Error fetching leaves: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while fetching leaves. ' . $e->getMessage());
        }
    }

    // Show leave create form
    public function create()
    {
        try {
            return view('leave.create');
        } catch (\Exception $e) {
            Log::error('Error opening leave create form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    // Save new leave
    public function save(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date_from',
            'leave_type' => 'required',
            'reason' => 'required',
        ]);
        try {
            $exists = Leave::where('user_id', Auth::id())
                ->where(function ($query) use ($request) {
                    $query->whereBetween('date_from', [$request->date_from, $request->date_to])
                        ->orWhereBetween('date_to', [$request->date_from, $request->date_to])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('date_from', '<=', $request->date_from)
                                ->where('date_to', '>=', $request->date_to);
                        });
                })
                ->exists();
            if ($exists) {
                return back()->withErrors(['date_from' => 'You already have a leave in this date range.'])->withInput();
            }
            $leave = Leave::create([
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'leave_type' => $request->leave_type,
                'reason' => $request->reason,
                'status' => 'pending',
                'team_leader_status' => 'pending',
            ]);
            $this->handleNewLeaveApplicationNotification($leave);
            return redirect()->route('leaves')->with('success', 'Leave sent successfully.');
        } catch (\Exception $e) {
            Log::error('Error saving leave: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to send leave.' . $e->getMessage());
        }
    }

    // Delete leave
    public function delete($id)
    {
        try {
            $leave = Leave::findOrFail($id);
            $leave->delete();
            return redirect()->route('leaves')->with('success', 'Leave deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting leave: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete leave.');
        }
    }



    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate(['status' => 'required|in:pending,accepted,rejected']);
            $leave = Leave::findOrFail($id);
            $oldStatus = $leave->status;

            // ... your log statements ...

            $leave->status = $request->status;
            $leave->reject_reason = ($request->status === 'rejected') ? $request->reject_reason : null;
            $leave->save();

            if ($leave->status !== $oldStatus) {
                // 1. Notify the employee whose leave was updated (Correct)
                $this->notifyOfLeaveStatusUpdate($leave, $leave->status, $leave->reject_reason);

                // 2. Notify Manager/HR/TLs if a supervisor updated the status
                $userWhoUpdated = Auth::user();
                if (in_array($userWhoUpdated->role, ['team_leader', 'manager', 'hr'])) {
                    $this->notifySupervisorsOfLeaveActivity(
                        $userWhoUpdated,
                        "updated the status of a leave for {$leave->user->full_name} to '{$leave->status}'",
                        $leave
                    );
                }
            }


            return back()->with('success', 'Leave status updated successfully.');
        } catch (\Exception $e) {
            // ... your error handling ...
            return back()->with('error', 'Failed to update leave status.');
        }
    }

    public function updateTeamLeaderStatus(Request $request, $id)
    {
        try {
            $leave = Leave::findOrFail($id);
            if (Auth::user()->role !== 'team_leader') {
                return back()->withErrors('You are not authorized to perform this action.');
            }
            $request->validate(['team_leader_status' => 'required|in:pending,accepted,rejected']);
            $oldStatus = $leave->team_leader_status;

            $leave->team_leader_status = $request->team_leader_status;
            if ($request->team_leader_status === 'rejected') {
                $leave->reject_reason = $request->reject_reason;
            }
            $leave->save();

            if ($leave->team_leader_status !== $oldStatus) {
                // 1. Notify the employee (Your existing logic - CORRECT)
                $this->notifyOfLeaveStatusUpdate($leave, $request->team_leader_status, $request->reject_reason);

                // 2. NEW: ALSO notify Manager/HR of this action
                $userWhoUpdated = Auth::user();
                $this->notifySupervisorsOfLeaveActivity(
                    $userWhoUpdated,
                    "updated a leave for {$leave->user->full_name} to '{$request->team_leader_status}'",
                    $leave
                );
            }

            return back()->with('success', 'Team leader status updated successfully.');
        } catch (\Exception $e) {
            // ... your error handling ...
            return back()->with('error', 'Failed to update team leader status.');
        }
    }


    public function leavebulkDelete(Request $request)
    {
        $ids = $request->input('leave_ids', []);
        if (!$ids || !is_array($ids) || count($ids) === 0) {
            return back()->with('error', 'No leaves selected for deletion.');
        }
        try {
            $deletedCount = Leave::whereIn('id', $ids)->delete();
            return back()->with('success', "Successfully deleted {$deletedCount} leave(s).");
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting leaves.');
        }
    }

    public function edit($id)
    {
        $leave = Leave::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('team_leader_status', 'pending')
            ->firstOrFail();

        return view('leave.edit', compact('leave'));
    }

    public function update(Request $request, $id)
    {
        $leave = Leave::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('team_leader_status', 'pending')
            ->firstOrFail();

        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'leave_type' => 'required',
            'reason' => 'required',
        ]);

        $leave->update([
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'leave_type' => $request->leave_type,
            'reason' => $request->reason,
        ]);

        $this->handleupdateLeaveApplicationNotification($leave);
        return redirect()->route('leaves')->with('success', 'Leave updated successfully.');
    }
}
