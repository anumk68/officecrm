<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Projects;
use App\Models\Task;
use App\Models\TaskTimeLog;
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
            $projects = Projects::get();
            return view("project.index", compact("projects"));
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
        if (!in_array(Auth::user()->role, ['manager', 'team_leader'])) {
            abort(403, 'You are not allowed to access this project.');
        }
        try {
            $users = User::all();
            $approvedLeads = Lead::where('status', 'converted')
                ->whereHas('approvable', function ($q) {
                    $q->where('status', 'approved');
                })
                ->get();
            return view('project.form', compact('users', 'approvedLeads'));
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
            'project_name'      => 'required|string|max:255',
            'company_name'      => 'nullable|string|max:255',
            'service_type'      => 'nullable|string|max:255',
            'sub_service'       => 'nullable|string|max:255',

            'deadline'          => 'required|date',
            'priority'          => 'required|string',
            'status'            => 'required|string',
            'color'             => 'nullable|string',

            // Domain
            'domain_name'       => 'nullable|string',
            'domain_registrar'  => 'nullable|string',
            'domain_expiry'     => 'nullable|date',

            // Hosting
            'hosting_provider'  => 'nullable|string',
            'server_type'       => 'nullable|string',
            'hosting_expiry'    => 'nullable|date',
            'cpanel_url'        => 'nullable|string',
            'cpanel_username'   => 'nullable|string',
            'cpanel_password'   => 'nullable|string',

            // Email
            'project_email'         => 'nullable|email',
            'project_email_password' => 'nullable|string',
            'smtp_host'             => 'nullable|string',
            'smtp_port'             => 'nullable|string',

            // Backup + Admin Panel
            'backup_email'      => 'nullable|email',
            'admin_url'         => 'nullable|string',
            'admin_username'    => 'nullable|string',
            'admin_password'    => 'nullable|string',

            // External Credentials
            'other_credentials' => 'nullable|string',
        ]);

        try {
            $service = $request->service_type === 'other'
                ? $request->service_type_custom
                : $request->service_type;

            $subService = $request->sub_service === 'other'
                ? $request->sub_service_custom
                : $request->sub_service;

            Projects::create([
                'project_name'      => $request->project_name,
                'company_name'      => $request->company_name,

                'service_type'      => $service,
                'sub_service'       => $subService,

                'deadline'          => $request->deadline,
                'priority'          => $request->priority,
                'status'            => $request->status,
                'color'             => $request->color,

                // Domain
                'domain_name'       => $request->domain_name,
                'domain_registrar'  => $request->domain_registrar,
                'domain_expiry'     => $request->domain_expiry,

                // Hosting
                'hosting_provider'  => $request->hosting_provider,
                'server_type'       => $request->server_type,
                'hosting_expiry'    => $request->hosting_expiry,
                'cpanel_url'        => $request->cpanel_url,
                'cpanel_username'   => $request->cpanel_username,
                'cpanel_password'   => $request->cpanel_password,

                // Email credentials
                'project_email'         => $request->project_email,
                'project_email_password' => $request->project_email_password,
                'smtp_host'             => $request->smtp_host,
                'smtp_port'             => $request->smtp_port,

                // Backup + Admin Panel
                'backup_email'      => $request->backup_email,
                'admin_url'         => $request->admin_url,
                'admin_username'    => $request->admin_username,
                'admin_password'    => $request->admin_password,

                // Other Credentials
                'other_credentials' => $request->other_credentials,
                'lead_id' => $request->lead_id,
            ]);


            return redirect()->route('projects')
                ->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            Log::error("Project save failed: " . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Failed to create project. ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'project_name'      => 'required|string|max:255',
            'company_name'      => 'nullable|string|max:255',
            'service_type'      => 'nullable|string|max:255',
            'sub_service'       => 'nullable|string|max:255',

            'deadline'          => 'required|date',
            'priority'          => 'required|string',
            'status'            => 'required|string',
            'color'             => 'nullable|string',

            // Domain
            'domain_name'       => 'nullable|string',
            'domain_registrar'  => 'nullable|string',
            'domain_expiry'     => 'nullable|date',

            // Hosting
            'hosting_provider'  => 'nullable|string',
            'server_type'       => 'nullable|string',
            'hosting_expiry'    => 'nullable|date',
            'cpanel_url'        => 'nullable|string',
            'cpanel_username'   => 'nullable|string',
            'cpanel_password'   => 'nullable|string',

            // Email & SMTP
            'project_email'         => 'nullable|email',
            'project_email_password' => 'nullable|string',
            'smtp_host'             => 'nullable|string',
            'smtp_port'             => 'nullable|string',

            // Admin Panel
            'backup_email'      => 'nullable|email',
            'admin_url'         => 'nullable|string',
            'admin_username'    => 'nullable|string',
            'admin_password'    => 'nullable|string',
            'other_credentials' => 'nullable|string',
        ]);

        try {

            $project = Projects::findOrFail($id);
            /** ---------------------------------------------
             * CUSTOM SERVICE TYPE HANDLING
             * ----------------------------------------------*/
            $serviceType = $request->service_type === 'other'
                ? $request->service_type_other
                : $request->service_type;

            $subService = $request->sub_service === 'other'
                ? $request->sub_service_other
                : $request->sub_service;

            /** ---------------------------------------------
             * UPDATE VALUES
             * ----------------------------------------------*/
            $data = $request->all();
            $data['service_type'] = $serviceType;
            $data['sub_service']  = $subService;

            $project->update($data);
            return redirect()->back()->with([
                'success' => 'Project updated successfully.',
                'activeTab' => 'projectDetails'
            ]);
        } catch (\Exception $e) {

            Log::error("Project update failed: " . $e->getMessage());

            return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
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


    // public function detail(Request $request, $id)
    // {
    //     if (!in_array(Auth::user()->role, ['manager', 'team_leader'])) {
    //         abort(403, 'You are not allowed to access this project.');
    //     }
    //     $project = Projects::findOrFail($id);

    //     $status = $request->status;
    //     $query = Task::where('created_by', Auth::user()->id)
    //         ->where('project_id', $project->id);

    //     if (!empty($status)) {
    //         $query->where('status', $status);
    //     }

    //     $tasks = $query->latest()->get();
    //     $users = User::all();

    //     return view('project.detail', compact('project', 'tasks', 'users', 'status'));
    // }

    public function detail(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['manager', 'team_leader'])) {
            abort(403, 'You are not allowed to access this project.');
        }

        $project = Projects::findOrFail($id);
        $status = $request->status;
        $query = Task::where('project_id', $project->id);

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $tasks = $query->latest()->get();
        $users = User::all();

        // -----------------------------
        // TOTAL PROJECT TIME ANALYTICS
        // -----------------------------
        $taskIds = Task::where('project_id', $project->id)->pluck('id');

        $logs = TaskTimeLog::whereIn('task_id', $taskIds)->get();

        $totalMinutes = 0;

        foreach ($logs as $log) {
            if ($log->start_time) {
                $endTime = $log->end_time ?? now();
                $totalMinutes += \Carbon\Carbon::parse($log->start_time)
                    ->diffInMinutes(\Carbon\Carbon::parse($endTime));
            }
        }
         $approvedLeads = Lead::where('status', 'converted')
                ->whereHas('approvable', function ($q) {
                    $q->where('status', 'approved');
                })
                ->get();

        return view('project.detail', compact(
            'project',
            'tasks',
            'users',
            'status',
            'totalMinutes',
            'approvedLeads'
        ));
    }
}
