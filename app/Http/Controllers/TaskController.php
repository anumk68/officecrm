<?php

namespace App\Http\Controllers;

use App\Mail\TaskAssignedMail;

use App\Models\Task;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\TaskStatusUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    use NotifiesUsers;
    /**
     * Show tasks assigned TO the logged-in user ("Assigned Me")
     */
    public function assignedMe()
    {
        try {
            $user = Auth::user();
            $tasks = Task::with(['assigner'])
                ->whereJsonContains('assigned_to', (string) $user->id)
                ->latest()
                ->get();
            $users = User::all();
            return view('dashboard.assigned_me', compact('tasks', 'users'));
        } catch (\Exception $e) {
            Log::error("AssignedMe error: " . $e->getMessage());
            return back()->with("error", "Failed to load assigned tasks.");
        }
    }

    /**
     * Show tasks assigned BY the logged-in user ("Assigned Other")
     */
    public function assignedOther()
    {
        try {
            $user = Auth::user();
            $tasks = Task::with(['assigner'])
                ->where('assigned_by', $user->id)
                ->latest()
                ->get();
            $users = User::all();

            return view('dashboard.assigned_other', compact('tasks', 'users'));
        } catch (\Exception $e) {
            Log::error("AssignedOther error: " . $e->getMessage());
            return back()->with("error", "Failed to load tasks you assigned." . $e->getMessage());
        }
    }


    /**
     * Show create task form
     */
    public function create()
    {
        try {
            $users = User::all();
            return view('tasks.create', compact('users'));
        } catch (\Exception $e) {
            Log::error("Create task view error: " . $e->getMessage());
            return back()->with("error", "Failed to load create task form.");
        }
    }

    /**
     * Store a new task
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'task' => 'required|string',
            'website' => 'required|string',
            'deadline' => 'required|date',
            'priority' => 'required|in:Low,Medium,High',
            'assigned_to' => 'required|array',
            'assigned_to.*' => 'exists:users,id',
        ]);

        try {
            $task = Task::create([
                'date' => $request->date,
                'task' => $request->task,
                'website' => $request->website,
                'deadline' => $request->deadline,
                'priority' => $request->priority,
                'assigned_to' => $request->assigned_to,
                'assigned_by' => Auth::id(),
            ]);

            $users = User::whereIn('id', $request->assigned_to)->get();
            $this->notifyOfNewTask($task, $users);

            foreach ($users as $user) {
                try {
                    Log::info("Sending mail to {$user->email}");
                    Mail::to($user->email)->send(new TaskAssignedMail($task, $user));
                    Log::info("Mail sent successfully to {$user->email}");
                } catch (\Exception $mailEx) {
                    Log::error("Mail failed to send to {$user->email}: " . $mailEx->getMessage());
                }
            }

            if (Auth::user()->role === 'team_leader') {
                return redirect()->route('tasks.assignedOther')->with('success', 'Task assigned successfully.');
            }
            return redirect()->route('dashboard')->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            Log::error("Task store error: " . $e->getMessage());
            return back()->withInput()->with("error", "Failed to create task." . $e->getMessage());
        }
    }

    /**
     * Update task status or assignment
     */
    public function update(Request $request, Task $task)
    {

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        try {
            $data = [
                'status' => $request->status ?? $task->status,
                'assigned_to' => $request->assigned_to ?? $task->assigned_to,
                'assigned_by' => $request->assigned_to ? Auth::id() : $task->assigned_by ?? null,
            ];
            if ($request->status === 'Completed') {
                $data['completed_by'] = Auth::id() ?? null;
                $data['completed_info'] = $request->completed_info ?? null;
            } else {
                $data['completed_by'] = null;
                $data['completed_info'] = null;
            }
            $task->update($data);
            return back()->with('success', 'Task updated.');
        } catch (\Exception $e) {
            Log::error("Task update error: " . $e->getMessage());
            return back()->with("error", "Failed to update task." . $e->getMessage());
        }
    }

    /**
     * Update task status and notify assigner
     */
    public function updateStatus(Request $request, Task $task)
    {
        // dd($request->all());
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        try {
            $task->status = $request->status;
            if ($request->status === 'Completed') {
                $task->completed_by = Auth::id();
                $task->completed_info = $request->completed_info;
            } else {
                $task->completed_info = null;
                $task->completed_by = null;
            }

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
            return redirect()->back()->with('success', 'Task status updated successfully.');
        } catch (\Exception $e) {
            Log::error("Task status update error: " . $e->getMessage());
            return back()->with("error", "Failed to update task status.");
        }
    }

    /**
     * Delete a task
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return back()->with('success', 'Task deleted.');
        } catch (\Exception $e) {
            Log::error("Task delete error: " . $e->getMessage());
            return back()->with("error", "Failed to delete task.");
        }
    }

    /**
     * Show trashed (soft deleted) tasks
     */
    public function trashed()
    {
        try {
            if (Auth::user()->role == 'team_member') {

                $tasks = Task::onlyTrashed()
                    ->whereJsonContains('assigned_to', (string) Auth::id())
                    ->get();
            } else {
                $tasks = Task::onlyTrashed()->where('assigned_by', Auth::id())
                    ->get();
            }
            return view('tasks.trashed', compact('tasks'));
        } catch (\Exception $e) {
            Log::error("Trashed tasks error: " . $e->getMessage());
            return back()->with("error", "Failed to load trashed tasks." . $e->getMessage());
        }
    }

    /**
     * Restore a soft-deleted task
     */
    public function restore($id)
    {
        try {
            $task = Task::withTrashed()->findOrFail($id);
            $task->restore();
            return back()->with('success', 'Task restored.');
        } catch (\Exception $e) {
            Log::error("Task restore error: " . $e->getMessage());
            return back()->with("error", "Failed to restore task.");
        }
    }

    /**
     * Show completed tasks (Task history)
     */
    public function taskHistory()
    {
        try {
            $user = Auth::user();
            $tasks = Task::with('assigner')
                ->whereJsonContains('assigned_to', (string) $user->id)
                ->where('status', 'Completed')
                ->orderByDesc('updated_at')
                ->get();
            return view('tasks.taskhistory', compact('tasks'));
        } catch (\Exception $e) {
            Log::error("Task history error: " . $e->getMessage());
            return back()->with("error", "Failed to load task history.");
        }
    }

    /**
     * View a single updated task
     */
    public function ViewUpdatedTask(Task $task)
    {
        try {
            return view('tasks.view', compact('task'));
        } catch (\Exception $e) {
            Log::error("View task error: " . $e->getMessage());
            return back()->with("error", "Failed to view task.");
        }
    }

    public function taskbulkDelete(Request $request)
    {
        $ids = $request->task_ids;

        if (!$ids) {
            return back()->with('error', 'No tasks selected to delete.');
        }

        Task::whereIn('id', $ids)->delete();

        return back()->with('success', 'Selected tasks deleted successfully.');
    }
    public function taskbulkRestore(Request $request)
    {
        $ids = $request->task_ids;

        if (!$ids) {
            return back()->with('error', 'No tasks selected to restore.');
        }

        Task::withTrashed()->whereIn('id', $ids)->restore();

        return back()->with('success', 'Selected tasks restored successfully.');
    }

    public function taskbulkDeletePermanent(Request $request)
    {
        $ids = $request->task_ids;

        if (!$ids) {
            return back()->with('error', 'No tasks selected to delete.');
        }

        Task::withTrashed()->whereIn('id', $ids)->forceDelete();

        return back()->with('success', 'Selected tasks permanently deleted.');
    }


}
