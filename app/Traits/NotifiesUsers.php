<?php

namespace App\Traits;

use App\Events\BroadcastNotification;
use App\Models\Holiday;
use App\Models\HrRequest;
use App\Models\Information;
use App\Models\Lead;
use App\Models\Leave;
use App\Models\Notification;
use App\Models\Projects;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait NotifiesUsers
{
    // Define a constant for the generic event name to avoid typos
    protected const NOTIFICATION_EVENT_NAME = 'notification.received';

    /**
     * Notifies team leaders about a new leave application.
     */
    protected function notifyOfNewLeave(Leave $leave): void
    {
        $data = [
            'type' => 'leave_applied',
            'title' => 'New Leave Request',
            'message' => "{$leave->user->full_name} has applied for leave.",
            'url' => route('leaves'),
            'metadata' => ['leave_id' => $leave->id]
        ];

        event(new BroadcastNotification('team-leader-notifications', self::NOTIFICATION_EVENT_NAME, $data));
    }
    protected function notifyOfNewTask(Task $task, Collection $assignedUsers): void
    {
        if ($assignedUsers->isEmpty()) {
            return;
        }

        $assigner = Auth::user();
        $allNotificationUsers = collect($assignedUsers);

        if ($assigner->role === 'team_leader') {

            $extraUsers = User::whereIn('role', ['manager', 'hr'])->get();


            $position = strtolower($assigner->position);
            if (str_contains($position, 'web_dev_team_leader')) {
                $teamType = 'web_dev_team_leader';
            } elseif (str_contains($position, 'seo_team_lead')) {
                $teamType = 'seo_team_lead';
            } elseif (str_contains($position, 'design')) {
                $teamType = 'design';
            } else {
                $teamType = null;
            }


            if ($teamType) {
                $sameDeptLeads = User::where('role', 'team_leader')
                    ->where('id', '!=', $assigner->id)
                    ->where('position', 'LIKE', '%' . $teamType . '%')
                    ->get();

                $extraUsers = $extraUsers->merge($sameDeptLeads);
            }

            $allNotificationUsers = $allNotificationUsers->merge($extraUsers);
            $allNotificationUsers->push($assigner);
        }

        $allNotificationUsers = $allNotificationUsers->unique('id');
        $channels = $allNotificationUsers
            ->map(fn(User $user) => 'App.Models.User.' . $user->id)
            ->all();

        $data = [
            'type' => 'task_assigned',
            'title' => 'New Task Assigned',
            'message' => "A new task '{$task->task}' has been created by {$assigner->full_name}.",
            'url' => route('teamMember', $task->id),
            'metadata' => [
                'task_id' => $task->id,
                'assigned_by' => $task->assigner->full_name,
            ],
        ];

        foreach ($allNotificationUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'role_targets' => null,
                'module' => 'task_assigned',
                'title' => $data['title'],
                'message' => $data['message'],
                'is_read' => false,
            ]);
        }

        event(new BroadcastNotification($channels, self::NOTIFICATION_EVENT_NAME, $data));
    }

    protected function notifyOfUserLogin(User $loggedInUser): void
    {
        $data = [
            'type' => 'user_logged_in',
            'title' => 'User Logged In',
            'message' => "{$loggedInUser->full_name} has logged in.",
            'url' => route('dashboard'),
            'metadata' => ['user_id' => $loggedInUser->id]
        ];
        event(new BroadcastNotification('online-users', self::NOTIFICATION_EVENT_NAME, $data));
    }

    protected function notifyOfNewHoliday(Holiday $holiday): void
    {
        $channels = User::pluck('id')->map(fn($id) => "App.Models.User.$id")->all();
        $data = [
            'type' => 'holiday_added',
            'title' => 'New Holiday Added',
            'message' => "A new holiday '{$holiday->title}' has been added for date {$holiday->holiday_date}.",
            'url' => route('holiday.index'),
            'metadata' => [
                'holiday_id' => $holiday->id,
                'holiday_date' => $holiday->holiday_date
            ]
        ];

        Notification::create([
            'user_id' => null,
            'role_targets' => ["all"],
            'module' => 'holiday_added',
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => false,
        ]);

        event(new BroadcastNotification('all-users', self::NOTIFICATION_EVENT_NAME, $data));
    }
    protected function notifyOfNewInformation(Information $Information): void
    {
        $data = [
            'type' => 'information_added',
            'title' => 'New Information Added',
            'message' => "A new Information '{$Information->title}' has been added for date {$Information->information_date}.",
            'url' => route('informations.index'),
            'metadata' => [
                'information_id' => $Information->id,
                'information_date' => $Information->information_date
            ]
        ];

        Notification::create([
            'user_id' => null,
            'role_targets' => ['all'],
            'module' => 'information',
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => false,
        ]);

        event(new BroadcastNotification('all-users', self::NOTIFICATION_EVENT_NAME, $data));
    }

    protected function notifyOfLeaveStatusUpdate(Leave $leave, string $newStatus, ?string $rejectReason = null): void
    {
        $applicant = $leave->load('user')->user;
        $department = strtolower($applicant->department ?? '');
        $recipients = User::whereIn('role', ['hr', 'manager'])->get();

        if ($department) {
            $teamLeaders = User::where('role', 'team_leader')
                ->whereRaw('LOWER(department) = ?', [$department])
                ->get();

            $recipients = $recipients->merge($teamLeaders);
        }

        $recipients = $recipients->unique('id');

        $message = "{$applicant->full_name} ({$applicant->department}) leave request from {$leave->date_from} to {$leave->date_to} has been {$newStatus}.";
        if ($rejectReason) {
            $message .= " Reason: {$rejectReason}";
        }

        $data = [
            'type'    => 'leave_status_updated',
            'title'   => 'Leave Status Updated',
            'message' => $message,
            'url'     => route('leaves'),
            'metadata' => ['leave_id' => $leave->id],
        ];

        Notification::create([
            'user_id' => $applicant->id,
            'role_targets' => null,
            'module' => 'leave_status_updated',
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => false,
        ]);

        foreach ($recipients as $user) {
            Notification::create([
                'user_id' => $user->id,
                'role_targets' => null,
                'module' => 'leave_status_updated',
                'title' => $data['title'],
                'message' => $data['message'],
                'is_read' => false,
            ]);
        }

        $channels = $recipients->map(fn($u) => 'App.Models.User.' . $u->id)->all();
        $channels[] = 'App.Models.User.' . $applicant->id;
        event(new BroadcastNotification($channels, self::NOTIFICATION_EVENT_NAME, $data));
        Log::info("Leave status update notification SENT for leave ID {$leave->id} ({$newStatus}) to department: {$department}");
    }

    protected function notifyLeadershipOfLeaveActivity(User $teamLeader, string $actionDescription, Leave $leave): void
    {
        if ($teamLeader->role !== 'team_leader') {
            return;
        }
        $data = [
            'type' => 'leave_activity_info',
            'title' => 'Leave Activity by Team Leader',
            'message' => "{$teamLeader->full_name} has {$actionDescription}.",
            'url' => route('leaves'),
            'metadata' => [
                'leave_id' => $leave->id,
                'team_leader_id' => $teamLeader->id,
            ]
        ];
        event(new BroadcastNotification('leadership-notifications', self::NOTIFICATION_EVENT_NAME, $data));
        Log::info("Notified leadership of leave activity by {$teamLeader->full_name}.");
    }
    protected function notifyProjectAssignment(Projects $project): void
    {
        $manager = Auth::user();
        $assignedUser = User::find($project->assigned_to);

        if (!$assignedUser) {
            Log::warning("Could not send project assignment notification. User with ID {$project->assigned_to} not found.");
            return;
        }

        $data = [
            'type' => 'project_assigned',
            'title' => 'New Project Assigned',
            'message' => "You have been assigned a new project: '{$project->project_name}'.",
            'url' => route('projects'),
            'priority' => $project->priority,
            'metadata' => ['project_id' => $project->id],
        ];

        Notification::create([
            'user_id' => $assignedUser->id,
            'role_targets' => null,
            'module' => 'project_assigned',
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => false,
        ]);

        event(new BroadcastNotification(
            'App.Models.User.' . $assignedUser->id,
            self::NOTIFICATION_EVENT_NAME,
            $data
        ));

        $recipients = User::whereIn('role', ['hr', 'manager'])->get();

        $department = strtolower($assignedUser->department ?? '');
        if ($department) {
            $teamLeaders = User::where('role', 'team_leader')
                ->whereRaw('LOWER(department) = ?', [$department])
                ->get();

            $recipients = $recipients->merge($teamLeaders);
        }


        $recipients = $recipients->where('id', '!=', $assignedUser->id)->unique('id');


        foreach ($recipients as $user) {
            $notifyData = [
                'type' => 'project_assigned',
                'title' => 'New Project Assigned in Your Department',
                'message' => "{$assignedUser->full_name} ({$assignedUser->department}) has been assigned project '{$project->project_name}'.",
                'url' => route('projects'),
                'priority' => $project->priority,
                'metadata' => ['project_id' => $project->id],
            ];

            Notification::create([
                'user_id' => $user->id,
                'role_targets' => null,
                'module' => 'project_assigned',
                'title' => $notifyData['title'],
                'message' => $notifyData['message'],
                'is_read' => false,
            ]);

            event(new BroadcastNotification(
                'App.Models.User.' . $user->id,
                self::NOTIFICATION_EVENT_NAME,
                $notifyData
            ));
        }

        if ($manager->id !== $assignedUser->id) {
            $dataForManager = [
                'type' => 'project_assigned',
                'title' => 'Project Assigned Successfully',
                'message' => "You assigned project '{$project->project_name}' to {$assignedUser->full_name}.",
                'url' => route('projects'),
                'priority' => $project->priority,
                'metadata' => ['project_id' => $project->id],
            ];

            Notification::create([
                'user_id' => $manager->id,
                'role_targets' => null,
                'module' => 'project_assigned',
                'title' => $dataForManager['title'],
                'message' => $dataForManager['message'],
                'is_read' => false,
            ]);

            event(new BroadcastNotification(
                'App.Models.User.' . $manager->id,
                self::NOTIFICATION_EVENT_NAME,
                $dataForManager
            ));
        }
    }

    public function updateProjectStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
        ]);

        try {
            $project = Projects::findOrFail($id);
            $oldStatus = $project->status;

            // Decode old assigned users (ensure array)
            $oldAssigneeIds = is_array($project->assigned_to)
                ? $project->assigned_to
                : json_decode($project->assigned_to ?? '[]', true);

            // Update fields
            $project->status = $request->status;
            $project->assigned_to = json_encode($request->assigned_to ?? []);
            $project->assigned_by = Auth::id();

            if ($project->isDirty()) {
                $project->save();
                $this->notifyOfProjectUpdate($project, $oldStatus, $oldAssigneeIds);
            }

            return back()->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            Log::error("Project update failed: " . $e->getMessage());
            return back()->with('error', 'Failed to update project.');
        }
    }

    protected function notifyOfProjectUpdate(Projects $project, string $oldStatus, $oldAssigneeIds): void
    {
        $manager = Auth::user();

        // Decode both old and new assigned_to as arrays
        $oldAssigneeIds = is_string($oldAssigneeIds) ? json_decode($oldAssigneeIds, true) : (array) $oldAssigneeIds;
        $newAssigneeIds = is_string($project->assigned_to) ? json_decode($project->assigned_to, true) : (array) $project->assigned_to;

        $addedAssignees = array_diff($newAssigneeIds, $oldAssigneeIds);
        $removedAssignees = array_diff($oldAssigneeIds, $newAssigneeIds);

        foreach ($addedAssignees as $newAssigneeId) {
            $newAssignee = User::find($newAssigneeId);
            if (!$newAssignee) continue;

            $department = strtolower($newAssignee->department ?? '');
            $recipients = User::whereIn('role', ['hr', 'manager'])->get();

            if ($department) {
                $teamLeaders = User::where('role', 'team_leader')
                    ->whereRaw('LOWER(department) = ?', [$department])
                    ->get();
                $recipients = $recipients->merge($teamLeaders);
            }

            $recipients = $recipients->unique('id');

            // Notify new assignee
            Notification::create([
                'user_id'      => $newAssignee->id,
                'module'       => 'project_reassigned',
                'title'        => 'Project Assigned',
                'message'      => "You have been assigned to project '{$project->project_name}'.",
                'is_read'      => false,
            ]);

            event(new BroadcastNotification(
                'App.Models.User.' . $newAssignee->id,
                self::NOTIFICATION_EVENT_NAME,
                [
                    'type' => 'project_assigned',
                    'title' => 'Project Assigned',
                    'message' => "You have been assigned to project '{$project->project_name}'.",
                    'url' => route('projects'),
                    'metadata' => ['project_id' => $project->id],
                ]
            ));

            // Notify HR, Manager, TLs
            foreach ($recipients as $user) {
                if ($user->id !== $newAssignee->id) {
                    Notification::create([
                        'user_id'      => $user->id,
                        'module'       => 'project_reassigned',
                        'title'        => 'New Project Assignment',
                        'message'      => "{$newAssignee->full_name} ({$newAssignee->department}) has been assigned to '{$project->project_name}'.",
                        'is_read'      => false,
                    ]);
                }
            }
        }

        // Notify removed assignees
        foreach ($removedAssignees as $oldId) {
            $oldUser = User::find($oldId);
            if (!$oldUser) continue;

            Notification::create([
                'user_id'      => $oldUser->id,
                'module'       => 'project_unassigned',
                'title'        => 'Project Unassigned',
                'message'      => "The project '{$project->project_name}' is no longer assigned to you.",
                'is_read'      => false,
            ]);
        }

        // If only status changed (not assignment)
        if (empty($addedAssignees) && empty($removedAssignees) && $project->status !== $oldStatus) {
            foreach ($newAssigneeIds as $uid) {
                Notification::create([
                    'user_id'      => $uid,
                    'module'       => 'project_status_update',
                    'title'        => 'Project Status Updated',
                    'message'      => "The project '{$project->project_name}' status changed to '{$project->status}'.",
                    'is_read'      => false,
                ]);
            }
        }
    }



    protected function handleNewLeaveApplicationNotification(Leave $leave): void
    {
        $applicant = $leave->load('user')->user;


        $department = strtolower($applicant->department ?? '');


        $recipients = User::whereIn('role', ['hr', 'manager'])->get();

        if ($department) {
            $teamLeaders = User::where('role', 'team_leader')
                ->whereRaw('LOWER(department) = ?', [$department])
                ->get();

            $recipients = $recipients->merge($teamLeaders);
        }

        $recipients = $recipients->unique('id');


        $data = [
            'type'    => 'leave_applied',
            'title'   => 'New Leave Request',
            'message' => "{$applicant->full_name} ({$applicant->department}) has applied for leave from {$leave->date_from} to {$leave->date_to}.",
            'url'     => route('leaves'),
            'metadata' => ['leave_id' => $leave->id],
        ];


        foreach ($recipients as $user) {
            Notification::create([
                'user_id' => $user->id,
                'role_targets' => null,
                'module' => 'leave_applied',
                'title' => $data['title'],
                'message' => $data['message'],
                'is_read' => false,
            ]);
        }

        $channels = $recipients->map(fn($u) => 'App.Models.User.' . $u->id)->all();
        event(new BroadcastNotification($channels, self::NOTIFICATION_EVENT_NAME, $data));
    }


    protected function handleupdateLeaveApplicationNotification(Leave $leave): void
    {
        $applicant = $leave->load('user')->user;

        $department = strtolower($applicant->department ?? '');
        $recipients = User::whereIn('role', ['hr', 'manager'])->get();

        if ($department) {
            $teamLeaders = User::where('role', 'team_leader')
                ->whereRaw('LOWER(department) = ?', [$department])
                ->get();

            $recipients = $recipients->merge($teamLeaders);
        }

        $recipients = $recipients->unique('id');


        $data = [
            'type'    => 'leave_applied',
            'title'   => 'Update Leave Request',
            'message' => "{$applicant->full_name} ({$applicant->department}) has applied for leave from {$leave->date_from} to {$leave->date_to}.",
            'url'     => route('leaves'),
            'metadata' => ['leave_id' => $leave->id],
        ];


        foreach ($recipients as $user) {
            Notification::create([
                'user_id' => $user->id,
                'role_targets' => null,
                'module' => 'leave_applied',
                'title' => $data['title'],
                'message' => $data['message'],
                'is_read' => false,
            ]);
        }

        $channels = $recipients->map(fn($u) => 'App.Models.User.' . $u->id)->all();
        // dd($channels);
        event(new BroadcastNotification($channels, self::NOTIFICATION_EVENT_NAME, $data));
    }



    protected function notifySupervisorsOfLeaveActivity(User $actionTaker, string $actionDescription, Leave $leave): void
    {
        $data = [
            'type'    => 'leave_activity_info',
            'title'   => 'Leave Status Update',
            'message' => "{$actionTaker->full_name} has {$actionDescription}.",
            'url'     => route('leaves'),
            'metadata' => ['leave_id' => $leave->id]
        ];

        $supervisors = User::whereIn('role', ['Manager', 'Team Leader', 'HR'])->get();

        foreach ($supervisors as $supervisor) {
            Notification::create([
                'user_id' => $supervisor->id,
                'type' => $data['type'],
                'title' => $data['title'],
                'message' => $data['message'],
                'url' => $data['url'],
                'metadata' => json_encode($data['metadata']),
                'is_read' => 0,
            ]);
        }
        event(new BroadcastNotification('supervisory-notifications', self::NOTIFICATION_EVENT_NAME, $data));
        Log::info("Notified supervisors of leave activity by {$actionTaker->full_name}.");
    }

    protected function notifyOfUserLogout(User $loggedInUser): void
    {
        $data = [
            'type' => 'user_logged_out',
            'title' => 'User Logged Out',
            'message' => "{$loggedInUser->full_name} has logged out.",
            'url' => route('dashboard'),
            'metadata' => ['user_id' => $loggedInUser->id]
        ];
        event(new BroadcastNotification('online-users', self::NOTIFICATION_EVENT_NAME, $data));
    }
    protected function notifyOfprofileupdate(User $loggedInUser): void
    {
        $data = [
            'type' => 'user_profile_update',
            'title' => 'Profile update Request',
            'message' => "{$loggedInUser->full_name} has updated their profile.",
            'url' => 'https://mail.google.com/',
            'metadata' => ['user_id' => $loggedInUser->id],
        ];

        Notification::create([
            'user_id' => null,
            'role_targets' => ['hr'],
            'module' => 'profile',
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => false,
        ]);
        event(new BroadcastNotification('hr-notifications', self::NOTIFICATION_EVENT_NAME, $data));
    }


    protected function notifyOfRequestCreate(User $loggedInUser, $requestType): void
    {
        $data = [
            'type' => 'user_request_create',
            'title' => 'New HR Request Created',
            'message' => "{$loggedInUser->full_name} has submitted a new {$requestType} request.",
            'url' => route('hr.requests.index'),
            'metadata' => [
                'user_id' => $loggedInUser->id,
                'request_type' => $requestType,
            ],
        ];

        Notification::create([
            'user_id' => null,
            'role_targets' => ['hr'],
            'module' => 'request',
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => false,
        ]);

        event(new BroadcastNotification('hr-notifications', self::NOTIFICATION_EVENT_NAME, $data));
    }

    protected function notifyUserOfRequestUpdate(User $hrUser, HrRequest $hrRequest): void
    {
        $data = [
            'type' => 'hr_request_update',
            'title' => 'Your HR Request has been updated',
            'message' => "{$hrUser->full_name} ({$hrUser->role}) has updated your {$hrRequest->type} request. Current status: " . ucfirst(str_replace('_', ' ', $hrRequest->status)) . ".",
            'url' => route('hr.requests.show', $hrRequest->id),
            'metadata' => [
                'hr_user_id' => $hrUser->id,
                'hr_request_id' => $hrRequest->id,
            ],
        ];

        Notification::create([
            'user_id' => $hrRequest->user_id,
            'role_targets' => null,
            'module' => 'request',
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => false,
        ]);

        $channels = 'App.Models.User.' . $hrRequest->user_id;
        // dd($channels);
        event(new BroadcastNotification($channels, self::NOTIFICATION_EVENT_NAME, $data));
    }

    protected function notifyDevelopmentTeamOfNewProject(Lead $lead, User $adminUser): void
    {
    
        $devUsers = User::where('role', 'team_leader')->get();
        foreach ($devUsers as $user) {
            $data = [
                'type' => 'new_project_ready',
                'title' => 'New Project Ready for Creation',
                'message' => "{$adminUser->full_name} has forwarded a new project from lead: {$lead->full_name}.",
                'url' => url('dashboard'),  // Project detail page
                'metadata' => [
                    'lead_id' => $lead->id,
                    'admin_user_id' => $adminUser->id,
                ],
            ];
 
            Notification::create([
                'user_id' => $user->id,
                'role_targets' => null,
                'module' => 'project',
                'title' => $data['title'],
                'message' => $data['message'],
                'is_read' => false,
            ]);
 
            $channel = 'App.Models.User.' . $user->id;
            event(new BroadcastNotification(
                $channel,
                self::NOTIFICATION_EVENT_NAME,
                $data
            ));
        }
    }
}
