<?php

namespace App\Http\Controllers;

use App\Mail\TaskAssignedMail;
use App\Models\Projects;
use App\Models\Task;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\TaskStatusUpdated;
use Illuminate\Support\Facades\DB;
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

    public function store(Request $request)
    {
        // Check raw input
        // dd($request->all());

        $request->validate([
            'project_id'      => 'required|exists:projects,id',
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'deadline_hour'   => 'required|numeric|min:0|max:23',
            'deadline_minute' => 'required|numeric|min:0|max:59',
        ]);

        try {

            Task::create([
                'project_id' => $request->project_id,
                'title'      => $request->title,
                'description' => $request->description,
                'deadline'   => $request->deadline_hour . ":" . $request->deadline_minute . ":00",
                'status'     => 'Pending',
                'assigned_to' => json_encode(['Anyone']),
                'created_by' => Auth::id(),
            ]);

            return back()->with('success', 'Task added successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to add task. ' . $e->getMessage());
        }
    }



    public function assignUser(Request $request, Task $task)
    {
        $assigned = is_string($task->assigned_to)
            ? json_decode($task->assigned_to, true)
            : (array) $task->assigned_to;

        $userId = $request->user_id;
        $checked = $request->checked;

        /* -----------------------------
       ANYONE LOGIC
    ------------------------------*/
        if ($userId === "Anyone") {

            if ($checked) {
                $assigned = ["Anyone"];
            } else {
                $assigned = [];
            }
        } else {

            // Remove "Anyone" if already set
            if (in_array("Anyone", $assigned)) {
                $assigned = [];
            }

            if ($checked) {
                if (!in_array($userId, $assigned)) {
                    $assigned[] = $userId;
                }
            } else {
                $assigned = array_filter($assigned, fn($id) => $id != $userId);
            }
        }

        // Save
        $task->assigned_to = array_values($assigned);
        $task->save();

        /* --------------------------------
       BUILD DISPLAY VALUES
    --------------------------------*/

        if (in_array("Anyone", $assigned)) {

            $btnText = "Anyone";
            $tooltipText = "Anyone can work on this task";
        } elseif (count($assigned)) {

            $users = User::whereIn('id', $assigned)->pluck('full_name')->toArray();

            // Tooltip contains full list
            $tooltipText = implode(', ', $users);

            // Button text: only first 3
            if (count($users) > 3) {
                $btnText = implode(', ', array_slice($users, 0, 3))
                    . " +" . (count($users) - 3) . " more";
            } else {
                $btnText = implode(', ', $users);
            }
        } else {
            $btnText = "Anyone";
            $tooltipText = "Anyone can work on this task";
        }

        return response()->json([
            "status"       => "success",
            "assigned_to"  => $assigned,
            "button_text"  => $btnText,
            "tooltip_text" => $tooltipText
        ]);
    }


    /**
     * Update task status and notify assigner
     */
    public function updateStatus(Request $request)
    {
        $task = Task::find($request->task_id);

        if (!$task) {
            return response()->json(['status' => 'error', 'message' => 'Task not found']);
        }

        $task->status = $request->status ?? 'Completed';
        $task->save();

        return response()->json(['status' => 'success']);
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
    public function ViewUpdatedTask($id)
    {
        try {
            $user = Auth::user();
            if ($user->role == 'team_member') {
                $user = Auth::user();
                $task = Task::with(['assigner'])
                    ->whereJsonContains('assigned_to', (string) $user->id)
                    ->findOrFail($id);
                $users = User::all();
            } elseif ($user->role == 'team_leader') {

                $task = Task::with(['assigner'])
                    ->where('assigned_by', $user->id)
                    ->findOrFail($id);
                $users = User::all();
            } else {

                $task = Task::with(['assigner'])

                    ->findOrFail($id);
                $users = User::all();
            }

            return view('tasks.view', compact('task', 'users'));
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


    public function ajaxTeamtask()
    {
        $userId = Auth::id();

        $projects = Projects::whereHas('tasks', function ($query) use ($userId) {
            $query->whereJsonContains('assigned_to', $userId)
                ->where('status', 'Pending');
        })
            ->with(['tasks' => function ($query) use ($userId) {
                $query->whereJsonContains('assigned_to', $userId)
                    ->orWhereJsonContains('assigned_to', 'Anyone')
                    ->with(['timeLogs' => function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    }]);
            }])
            ->get();

        foreach ($projects as $project) {
            foreach ($project->tasks as $task) {
                $task->spent_seconds = $task->timeLogs->sum('duration_seconds');
            }
        }

        return response()->json([
            "projects" => $projects
        ]);
    }
    public function getTaskLogs()
    {
        $rows = DB::table('task_time_logs as l')
            ->leftJoin('tasks as t', 't.id', '=', 'l.task_id')
            ->leftJoin('projects as p', 'p.id', '=', 't.project_id')
            ->select(
                'l.*',
                't.title as task_title',
                't.deadline',
                'p.project_name'
            )
            ->orderBy('l.start_time', 'DESC')
            ->get();

        // Group by WEEK
        $weeks = $rows->groupBy(function ($row) {
            return \Carbon\Carbon::parse($row->start_time)->startOfWeek()->format("Y-m-d");
        });

        $final = [];

        foreach ($weeks as $weekStart => $weekLogs) {

            $weekName = $this->getWeekLabel($weekStart);

            // total week seconds
            $weekSeconds = 0;

            // group by DAY
            $days = $weekLogs->groupBy(function ($row) {
                return \Carbon\Carbon::parse($row->start_time)->format("Y-m-d");
            });

            $dayData = [];

            foreach ($days as $day => $dayLogs) {

                $daySeconds = 0;

                foreach ($dayLogs as $l) {
                    $daySeconds += (strtotime($l->end_time ?? now()) - strtotime($l->start_time));
                }

                $dayData[] = [
                    "date" => $day,
                    "total_seconds" => $daySeconds,
                    "logs" => $dayLogs
                ];

                $weekSeconds += $daySeconds;
            }

            $final[] = [
                "week_start" => $weekStart,
                "label" => $weekName,
                "total_seconds" => $weekSeconds,
                "days" => $dayData
            ];
        }

        // render HTML
        $html = view('partials.task_logs_table', compact('final'))->render();

        return response()->json(['html' => $html]);
    }

    private function getWeekLabel($weekStart)
    {
        $start = \Carbon\Carbon::parse($weekStart);

        if ($start->isCurrentWeek()) return "THIS WEEK";
        if ($start->isLastWeek()) return "LAST WEEK";

        return $start->format("d M") . " - " . $start->copy()->endOfWeek()->format("d M");
    }

    public function saveExtraReason(Request $req)
    {
        $req->validate([
            'log_id' => 'required|integer',
            'extra_time_reason' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        DB::table('task_time_logs')
            ->where('id', $req->log_id)
            ->update([
                'extra_time_reason' => $req->extra_time_reason,
                'remarks' => $req->remarks,
                'extra_time_status' => 'Pending',
                'updated_at' => now(),
            ]);

        return response()->json(['status' => 'ok']);
    }



    public function taskedit($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function taskupdate(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline_hour' => 'required|numeric|min:0|max:23',
            'deadline_minute' => 'required|numeric|min:0|max:59',
        ]);

        $task = Task::findOrFail($request->task_id);

        $deadline = sprintf('%02d:%02d:00', $request->deadline_hour, $request->deadline_minute);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $deadline,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.'
        ]);
    }


    public function taskDetails($id)
{
    $task = Task::with(['project', 'creator', 'timeLogs'])->find($id);

    if (!$task) {
        return response()->json(['status' => 'error', 'message' => 'Task not found'], 404);
    }

    return response()->json([
        'status' => 'ok',
        'task' => $task
    ]);
}

}
