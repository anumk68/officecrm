<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Models\User;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProjectsController extends Controller
{
    use NotifiesUsers;
    /**
     * Show all projects based on user role
     */
    public function index()
    {
        try {
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
        } catch (\Exception $e) {
            Log::error("Projects fetch failed: " . $e->getMessage());
            return back()->with("error", "Failed to load projects.");
        }
    }

    /**
     * Show project create form
     */
    public function create()
    {
        try {
            $users = User::all();
            return view('project.form', compact('users'));
        } catch (\Exception $e) {
            Log::error("Project create form failed: " . $e->getMessage());
            return back()->with("error", "Failed to open project form.");
        }
    }

    /**
     * Store new project
     */
    public function save(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'project_name' => 'required|string|max:255',
            'deadline' => 'required',
            'priority' => 'required',
        ]);

        try {
            $project = Projects::create([
                'date' => $request->date,
                'project_name' => $request->project_name,
                'deadline' => $request->deadline,
                'priority' => $request->priority,
                'assigned_to' => $request->assigned_to,
                'status' => 'pending',
                'assigned_by' => Auth::id(),
            ]);
            $this->notifyProjectAssignment($project);
            return redirect()->route('projects')->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            Log::error("Project save failed: " . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create project.' . $e->getMessage());
        }
    }

    public function updateProjectStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        try {
            $project = Projects::findOrFail($id);
            $oldStatus = $project->status;
            $oldAssigneeId = $project->assigned_to;
            $project->status = $request->status;
            if ($request->filled('assigned_to') && $request->assigned_to != $oldAssigneeId) {
                $project->assigned_to = $request->assigned_to;
                $project->assigned_by = Auth::id();
            }
            if ($project->isDirty()) {
                $project->save();
                $this->notifyOfProjectUpdate($project, $oldStatus, $oldAssigneeId);
            }
            return back()->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            Log::error("Project update failed: " . $e->getMessage());
            return back()->with('error', 'Failed to update project.');
        }
    }

    public function deleteProject($id)
    {
        try {
            $project = Projects::findOrFail($id);
            $project->delete();

            return redirect()->route('projects')->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Project delete failed: " . $e->getMessage());
            return back()->with('error', 'Failed to delete project.');
        }
    }
    public function projectbulkDelete(Request $request)
    {
        $ids = explode(',', $request->project_ids);

        if (empty($ids)) {
            return back()->with('error', 'No projects selected.');
        }

        Projects::whereIn('id', $ids)->delete();

        return back()->with('success', 'Selected projects deleted successfully.');
    }

}
