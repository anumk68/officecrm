<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProjectsController extends Controller
{
    //

    // public function index()
    // {
    //     $user = Auth::user();
    //     $projects = Projects::with(['assignedUser', 'assigner',])->where('assigned_by', $user->id)->orWhere('assigned_to', $user->id)->get();
    //     $users = User::all();
    //     return view("project.view", compact("projects", 'users'));
    // }
    public function index()
    {
        $user = Auth::user();
        $projects = Projects::with(['assignedUser', 'assigner']);
        if ($user->role === 'manager') {
            $projects = $projects->get();
        } else {
            $projects = $projects
                ->where('assigned_by', $user->id)
                ->orWhere('assigned_to', $user->id)
                ->get();
        }
        $users = User::all();
        return view("project.view", compact("projects", "users"));
    }

    public function create()
    {
        $users = User::all();
        return view('project.form', compact('users'));
    }
    public function save(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'project_name' => 'required',
            'deadline' => 'required',
            'priority' => 'required',
        ]);
        Projects::create([
            'date' => $request->date,
            'project_name' => $request->project_name,
            'deadline' => $request->deadline,
            'priority' => $request->priority,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => Auth::id(),
        ]);
        return redirect()->route('dashboard')->with('success', 'Project created successfully.');
    }
    public function updateProjectStatus(Request $request, $id)
    {
        $project = Projects::find($id);
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        if ($request->has('assigned_to') && $request->assigned_to != $project->assigned_to) {
            $project->assigned_to = $request->assigned_to;
            $project->assigned_by = Auth::id();
        }
        $project->status = $request->status;
        $project->save();
        return back()->with('success', 'Project updated successfully.');
    }
    public function deleteProject($id)
    {
        $project = Projects::find($id);
        $project->delete();
        return redirect()->route('projects');
    }

}
