<?php
namespace App\Http\Controllers;
use App\Models\Task;
use App\Models\TaskTimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    /* ============================================================
       MAIN USER REPORT API  
       ============================================================ */
    public function userReport(Request $request)
    {
        $userId = $request->user_id;
        $filter = $request->filter ?? 'monthly';

        if (!$userId) {
            return response()->json(['error' => 'User required'], 400);
        }

        // DATE RANGE
        switch ($filter) {
            case 'daily':
                $start = Carbon::today();
                $end   = Carbon::today();
                break;

            case 'weekly':
                $start = Carbon::now()->startOfWeek();
                $end   = Carbon::now()->endOfWeek();
                break;

            case 'monthly':
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
                break;

            case 'custom':
                $start = $request->start_date;
                $end   = $request->end_date;
                break;

            default:
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
        }

        /* ============================================================
           1. GET TASKS ASSIGNED TO USER
           ============================================================ */
        $tasks = Task::where(function ($q) use ($userId) {
            $q->whereJsonContains('assigned_to', (int)$userId)
                ->orWhereJsonContains('assigned_to', (string)$userId);
        })
            ->whereBetween('created_at', [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end)->endOfDay()
            ])
            ->orderBy('deadline', 'ASC')
            ->get();

        $tasksReport = $tasks->map(function ($task) use ($userId) {

            $timeLogs = TaskTimeLog::where('task_id', $task->id)
                ->where('user_id', $userId)
                ->get();

            $totalSeconds = $timeLogs->sum('duration_seconds');

            $extraSeconds = 0;
            $deadline = $task->deadline ? Carbon::parse($task->deadline) : null;

            if ($task->status == 'Completed' && $task->updated_at && $deadline) {
                $completedAt = Carbon::parse($task->updated_at);

                if ($completedAt->gt($deadline)) {
                    $extraSeconds = $completedAt->diffInSeconds($deadline);
                }
            }

            return [
                'id' => $task->id,
                'task' => $task->title,
                'status' => $task->status,
                'status_color' => $this->color($task->status),
                'assigned_at' => $task->created_at,
                'deadline' => $task->deadline,
                'completed_at' => $task->status == 'Completed' ? $task->updated_at : null,
                'total_work_seconds' => $totalSeconds,
                'total_work_readable' => gmdate("H:i:s", $totalSeconds),
                'extra_seconds' => $extraSeconds,
                'extra_readable' => gmdate("H:i:s", $extraSeconds),
            ];
        });

        $counts = [
            'Completed' => $tasksReport->where('status', 'Completed')->count(),
            'Pending' => $tasksReport->where('status', 'Pending')->count(),
            'In Progress' => $tasksReport->where('status', 'In-Progress')->count(),
        ];

        $completedTasks = $tasksReport->where('status', 'Completed');

        $dates = [];
        $count = [];

        $period = Carbon::parse($start)->daysUntil($end);

        foreach ($period as $day) {
            $d = $day->format('Y-m-d');
            $dates[] = $d;

            $count[] = $completedTasks->filter(function ($t) use ($d) {
                return Carbon::parse($t['completed_at'])->format('Y-m-d') == $d;
            })->count();
        }

        return response()->json([
            'tasks' => $tasksReport,
            'counts' => $counts,
            'dates' => $dates,
            'line_counts' => $count
        ]);
    }

    private function color($status)
    {
        return match ($status) {
            'Completed' => 'success',
            'Pending' => 'warning',
            'In Progress' => 'info',
            default => 'secondary',
        };
    }
}
