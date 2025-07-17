<?php

namespace App\Http\Controllers;

use App\Models\Remark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;

class RemarkController extends Controller
{
    // public function storeRemark(Request $request, $taskId)
    // {
    //     $request->validate([
    //         'text' => 'required|string|max:1000',
    //     ]);
    //     $remark = new Remark();
    //     $remark->task_id = $taskId;
    //     $remark->user_id = Auth::id();
    //     $remark->text = $request->text;
    //     $remark->save();
    //     return response()->json([
    //         'success' => true,
    //         'remarks' => Remark::where('task_id', $taskId)
    //             ->latest()
    //             ->with('user:id,name')
    //             ->get(['id', 'text', 'user_id']),
    //     ]);
    // }
    public function destroy($id)
    {
        $remark = Remark::findOrFail($id);
        if ($remark->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $remark->delete();
        return redirect()->back()->with('success', 'Remark deleted successfully.');
    }
    public function updateRemarks(Request $request, $taskId)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);
        $task = Task::find($taskId);
        $remark = new Remark();
        $remark->task_id = $task->id;
        $remark->user_id = Auth::id();
        $remark->text = $request->text;
        $remark->save();
        return response()->json([
            'success' => true,
            'remarks' => $task->remarks()->latest()->get(),
        ]);
    }
}
