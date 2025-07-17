<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\TaskStatusUpdated;

class TaskController extends Controller
{
    // Tasks assigned TO the logged-in user ("Assigned Me")
    public function assignedMe()
    {
        $user = Auth::user();
        $tasks = Task::with(['assignedUser', 'assigner'])
            ->where('assigned_to', $user->id)
            ->latest()
            ->get();
        $users = User::all();
        return view('dashboard.assigned_me', compact('tasks', 'users'));
    }
    // Tasks assigned BY the logged-in user to others ("Assigned Other")
    public function assignedOther()
    {
        $user = Auth::user();
        $tasks = Task::with(['assignedUser', 'assigner'])
            ->where('assigned_by', $user->id)
            ->latest()
            ->get();
        $users = User::all();
        return view('dashboard.assigned_other', compact('tasks', 'users'));
    }
    // Show create task form
    public function create()
    {
        $users = User::all();
        return view('tasks.create', compact('users'));
    }
    // Store new task
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'task' => 'required|string',
            'website' => 'required|string',
            'deadline' => 'required|date',
            'priority' => 'required|in:Low,Medium,High',
            'assigned_to' => 'required|exists:users,id',
        ]);
        Task::create([
            'date' => $request->date,
            'task' => $request->task,
            'website' => $request->website,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => Auth::id(),
        ]);
        if (Auth::user()->role === 'team_leader') {
            return redirect()->route('tasks.assignedOther')->with('success', 'Task assigned successfully.');
        }
        return redirect()->route('dashboard')->with('success', 'Task created successfully.');
    }

    // Update task status or assignment
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'nullable|in:Pending,In Progress,Completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        $task->update([
            'status' => $request->status,
            'assigned_to' => $request->assigned_to ?? $task->assigned_to,
            'assigned_by' => $request->assigned_to ? Auth::id() : $task->assigned_by,
        ]);
        return back()->with('success', 'Task updated.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);
        $task->status = $request->status;
        $task->save();
        if (method_exists($task, 'assignedByUser') && $task->assignedByUser) {
            $task->assignedByUser->notify(new TaskStatusUpdated($task));
        }
        if ($task->status === 'Completed') {
            return redirect()->route('tasks.history')->with('success', 'Task marked as completed.');
        }
        $route = match (Auth::user()->role) {
            'team_member', 'team_leader' => 'tasks.assignedMe',
            default => 'dashboard',
        };
        return redirect()->route($route)->with('success', 'Task status updated successfully.');
    }
    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Task deleted.');
    }
    public function trashed()
    {
        $tasks = Task::onlyTrashed()->with('assignedUser')->get();
        return view('tasks.trashed', compact('tasks'));
    }
    public function restore($id)
    {
        $task = Task::withTrashed()->findOrFail($id);
        $task->restore();
        return back()->with('success', 'Task restored.');
    }
    public function taskHistory()
    {
        $tasks = Task::with('assigner', 'assignedUser')
            ->where('assigned_to', Auth::id())
            ->where('status', 'Completed')
            ->orderByDesc('updated_at')
            ->get();
        return view('tasks.taskhistory', compact('tasks'));
    }
    public function ViewUpdatedTask(Task $task)
    {
        return view('tasks.view', compact('task'));
    }
}
