<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    public function index()
    {
        $tasks = Task::with('assignedUser')->latest()->get();
        return view('dashboard', compact('tasks', 'employees'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|string',
            'task' => 'required|string',
            'website' => 'required|url',
            'deadline' => 'required|date',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        Task::create($request->all());

        return redirect()->route('dashboard')->with('success', 'Task created successfully.');
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'status' => 'nullable|in:Pending,In Progress,Completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task->update([
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
        ]);

        return redirect()->route('dashboard')->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('dashboard')->with('success', 'Task soft-deleted.');
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
        return redirect()->route('dashboard')->with('success', 'Task restored.');
    }

    public function getList()
    {
        $teamList = Task::where('assigned_to', Auth::id())->get();
        return view('task', compact('teamList'));
    }
    public function storeDescription(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tasks,id',
            'description' => 'required',
        ]);
        $task = Task::find($request->id);
        $task->description = $request->description;
        $task->save();
        return redirect()->back()->with('success', 'Description added.');
    }

    public function updateDescription(Request $request)
    {
        $id = $request->id;
        $task = Task::find($id);
        $task->description = $request->description;
        $task->save();
        return redirect()->back()->with('success', 'Description updated.');
    }

}
