<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    //
    // public function index()
    // {
    //     $leaves = Leave::with('user')->latest()->get();
    //     return view('leave.index', compact('leaves'));
    // }

    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'manager') {
            $leaves = Leave::with('user')->latest()->get();
        } elseif ($user->role === 'team_leader') {
            $leaves = Leave::with('user')
                ->whereIn('role', ['team_leader', 'team_member'])
                ->latest()
                ->get();
        } elseif ($user->role === 'team_member') {
            $leaves = Leave::with('user')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }
        return view('leave.index', compact('leaves'));
    }
    public function create()
    {
        return view('leave.create');
    }
    public function save(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date|after_or_equal:today',
            'date_to' => 'required|date|after_or_equal:date_from',
            'leave_type' => 'required',
            'reason' => 'required',
        ]);
        Leave::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'leave_type' => $request->leave_type,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leaves')->with('success', 'Leave created.');
    }
    public function delete($id)
    {
        $leave = Leave::find($id);
        $leave->delete();
        return redirect()->route('leaves')->with('success', 'Leave Deleted Successfully');
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $leave = Leave::find($id);
        $leave->status = $request->status;
        $leave->save();

        return back()->with('success', 'Leave status updated.');
    }
}
