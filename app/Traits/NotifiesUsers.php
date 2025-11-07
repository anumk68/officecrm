<?php

namespace App\Traits;

use App\Events\BroadcastNotification;
use App\Models\Holiday;
use App\Models\Information;
use App\Models\Leave;
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
        $channels = $assignedUsers->map(fn(User $user) => 'App.Models.User.' . $user->id)->all();
        $data = [
            'type' => 'task_assigned',
            'title' => 'New Task Assigned to You',
            'message' => "A new task '{$task->task}' has been assigned to you.",
            'url' => route('teamMember', $task->id),
            'metadata' => [
                'task_id' => $task->id,
                'assigned_by' => $task->assigner->full_name
            ]
        ];
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
        event(new BroadcastNotification('all-users', self::NOTIFICATION_EVENT_NAME, $data));
    }

   protected function notifyOfLeaveStatusUpdate(Leave $leave, string $newStatus, ?string $rejectReason = null): void
    {
        $channel = 'App.Models.User.' . $leave->user_id;
        $data = [
            'type'    => 'leave_status_updated',
            'title'   => 'Your Leave Status Updated',
            'message' => "Your leave request from {$leave->date_from} to {$leave->date_to} has been {$newStatus}."
                         . ($rejectReason ? " Reason: {$rejectReason}" : ""),
            'url'     => route('leaves'),
            'metadata' => ['leave_id' => $leave->id]
        ];
        event(new BroadcastNotification($channel, self::NOTIFICATION_EVENT_NAME, $data));
        Log::info("Leave status update notification SENT to user ID: {$leave->user_id} on channel: {$channel}");
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
        $dataForAssignedUser = [
            'type' => 'project_assigned',
            'title' => 'New Project Assigned',
            'message' => "You have been assigned a new project: '{$project->project_name}'.",
            'url' => route('projects'),
            'priority' => $project->priority,
            'metadata' => [
                'project_id' => $project->id,
            ],
        ];
        event(new BroadcastNotification(
            'App.Models.User.' . $assignedUser->id,
            self::NOTIFICATION_EVENT_NAME,
            $dataForAssignedUser
        ));
        if ($manager->id === $assignedUser->id) {
            return;
        }
        $dataForManager = [
            'type' => 'project_assigned',
            'title' => 'Project Assigned Successfully',
            'message' => "You assigned project '{$project->project_name}' to {$assignedUser->full_name}.",
            'url' => route('projects'),
            'priority' => $project->priority,
            'metadata' => [
                'project_id' => $project->id,
            ],
        ];
        event(new BroadcastNotification(
            'App.Models.User.' . $manager->id,
            self::NOTIFICATION_EVENT_NAME,
            $dataForManager
        ));
    }
    protected function notifyOfProjectUpdate(Projects $project, string $oldStatus, ?int $oldAssigneeId): void
    {
        $manager = Auth::user();
        $newAssignee = User::find($project->assigned_to);
        if (!$newAssignee) {
            Log::warning("Cannot send project update notification. User with ID {$project->assigned_to} not found.");
            return;
        }
        if ($project->assigned_to !== $oldAssigneeId) {
            $dataForNewAssignee = [
                'type' => 'project_assigned',
                'title' => 'Project Re-Assigned to You',
                'message' => "The project '{$project->project_name}' has been assigned to you.",
                'url' => route('projects'),
                'metadata' => ['project_id' => $project->id],
            ];
            event(new BroadcastNotification('App.Models.User.' . $newAssignee->id, self::NOTIFICATION_EVENT_NAME, $dataForNewAssignee));
            if ($manager->id !== $newAssignee->id) {
                $dataForManager = [
                    'type' => 'project_assigned',
                    'title' => 'Project Re-Assigned',
                    'message' => "You re-assigned project '{$project->project_name}' to {$newAssignee->full_name}.",
                    'url' => route('projects'),
                    'metadata' => ['project_id' => $project->id],
                ];
                event(new BroadcastNotification('App.Models.User.' . $manager->id, self::NOTIFICATION_EVENT_NAME, $dataForManager));
            }
            if ($oldAssigneeId && $oldAssigneeId !== $newAssignee->id) {
                $dataForOldAssignee = [
                    'type' => 'project_update',
                    'title' => 'Project Un-Assigned',
                    'message' => "The project '{$project->project_name}' is no longer assigned to you.",
                    'url' => route('projects'),
                    'metadata' => ['project_id' => $project->id],
                ];
                event(new BroadcastNotification('App.Models.User.' . $oldAssigneeId, self::NOTIFICATION_EVENT_NAME, $dataForOldAssignee));
            }
        } else if ($project->status !== $oldStatus) {
            $data = [
                'type' => 'project_update',
                'title' => 'Project Status Updated',
                'message' => "The status of your project '{$project->project_name}' has been changed to '{$project->status}'.",
                'url' => route('projects'),
                'metadata' => [
                        'project_id' => $project->id,
                        'status' => $project->status
                    ],
            ];
            event(new BroadcastNotification('App.Models.User.' . $newAssignee->id, self::NOTIFICATION_EVENT_NAME, $data));
        }
    }

     protected function handleNewLeaveApplicationNotification(Leave $leave): void
    {
        $applicant = $leave->load('user')->user;
        $message = "{$applicant->full_name} ({$applicant->role}) has applied for leave.";
        $type = 'leave_applied';

        // Send a single notification to all supervisors, regardless of who applied.
        // The message makes it clear who it was.
        $data = [
            'type'    => $type,
            'title'   => 'New Leave Request',
            'message' => $message,
            'url'     => route('leaves'),
            'metadata'=> ['leave_id' => $leave->id]
        ];
        event(new BroadcastNotification('supervisory-notifications', self::NOTIFICATION_EVENT_NAME, $data));
    }

 protected function notifySupervisorsOfLeaveActivity(User $actionTaker, string $actionDescription, Leave $leave): void
    {
        $data = [
            'type'    => 'leave_activity_info',
            'title'   => 'Leave Status Update',
            'message' => "{$actionTaker->full_name} has {$actionDescription}.",
            'url'     => route('leaves'),
            'metadata'=> ['leave_id' => $leave->id]
        ];
        event(new BroadcastNotification('supervisory-notifications', self::NOTIFICATION_EVENT_NAME, $data));
        Log::info("Notified supervisors of leave activity by {$actionTaker->full_name}.");
    }

}
