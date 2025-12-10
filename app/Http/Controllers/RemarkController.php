<?php

namespace App\Http\Controllers;

use App\Models\Remark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class RemarkController extends Controller
{
    /**
     * Delete a remark
     */
    public function destroy($id)
    {
        try {
            $remark = Remark::findOrFail($id);
            $remark->delete();
            return redirect()->back()->with('success', 'Remark deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Remark delete failed: " . $e->getMessage());
            return back()->with("error", "Failed to delete remark.");
        }
    }

    /**
     * Add a new remark to a task
     */
    public function updateRemarks(Request $request, $taskId)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        try {
            $task = Task::findOrFail($taskId);

            $remark = new Remark();
            $remark->task_id = $task->id;
            $remark->user_id = Auth::id();
            $remark->text    = $request->text;
            $remark->save();

            return redirect()->back()->with('success', 'Remark added successfully.');
        } catch (\Exception $e) {
            Log::error("Remark add failed: " . $e->getMessage());
            return back()->withInput()->with("error", "Failed to add remark.");
        }
    }
}
