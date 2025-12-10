<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\TaskTimeLog;
use Carbon\Carbon;

class TaskTimerController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer|exists:tasks,id',
            'start_remaining_seconds' => 'nullable|integer',
        ]);

        $user = Auth::user();
        $task = Task::findOrFail($request->task_id);

        // Close any running log for this user+task (optional safety)
        TaskTimeLog::where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->whereNull('end_time')
            ->update([
                'end_time' => Carbon::now(),
                'duration_seconds' => 0 // we didn't compute, but mark closed — optional
            ]);

        $start = Carbon::now();

        $log = TaskTimeLog::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'start_time' => $start,
            'start_remaining_seconds' => $request->start_remaining_seconds ?? $this->parseDurationToSeconds($task->deadline ?? '00:00:00'),
        ]);

        return response()->json([
            'status' => 'ok',
            'log_id' => $log->id,
            'started_at' => $log->start_time->toDateTimeString(),
        ]);
    }
 
public function stop(Request $request)
{
    $request->validate([
        'log_id' => 'required|integer|exists:task_time_logs,id',
    ]);

    $user = Auth::user();
    $log = TaskTimeLog::findOrFail($request->log_id);

    if ($log->user_id !== $user->id) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
    }

    if ($log->end_time) {
        return response()->json(['status' => 'error', 'message' => 'Timer already stopped'], 400);
    }

    $end_time = now();

    // 1) Calculate duration
    $duration = $end_time->diffInSeconds($log->start_time);   

    // 2) Take expected seconds from DB
    $expected = $log->start_remaining_seconds;                 

    // 3) Overtime logic
    $isOvertime = $duration > $expected;                      // → true
    $overtimeSeconds = $isOvertime ? $duration - $expected : 0;   // → 3 sec

    // 4) Save to DB
    $log->update([
        'end_time'         => $end_time,
        'duration_seconds' => $duration,           // 63
        'is_overtime'      => $isOvertime,         // 1
        'overtime_seconds' => $overtimeSeconds,    // 3
        'extra_time'       => $overtimeSeconds,    // 3
    ]);

    return response()->json([
        'status'           => 'ok',
        'duration_seconds' => $duration,
        'is_overtime'      => $isOvertime,
        'overtime_seconds' => $overtimeSeconds,
        'extra_time'       => $overtimeSeconds
    ]);
}



   

    private function parseDurationToSeconds($hhmmss)
    {
        // expects "HH:MM:SS"
        $parts = explode(':', $hhmmss);
        if (count($parts) !== 3) return 0;
        return ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2];
    }



     // optional heartbeat to persist last seen (not strictly necessary)
    public function heartbeat(Request $request)
    {
        $request->validate([
            'log_id' => 'required|integer|exists:task_time_logs,id',
        ]);

        $log = TaskTimeLog::findOrFail($request->log_id);
        // you may update a 'updated_at' or last_ping column if added
        $log->touch();

        return response()->json(['status' => 'ok', 'ts' => now()->toDateTimeString()]);
    }

    public function activeTask()
{
    $user = Auth::user();

    $log = TaskTimeLog::with('task')
        ->where('user_id', $user->id)
        ->whereNull('end_time')
        ->first();

    if (!$log) {
        return response()->json(['running' => false]);
    }

    return response()->json([
        'running' => true,
        'log_id'  => $log->id,
        'task'    => $log->task,
        'started_at' => $log->start_time,
        'start_remaining_seconds' => $log->start_remaining_seconds
    ]);
}

}
