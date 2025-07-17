<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskStatusUpdated extends Notification
{
    use Queueable;

    protected Task $task;

    /**
     * Create a new notification instance.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database', 'broadcast', 'mail'];  // Remove 'mail' if email not needed
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Task Status Updated')
            ->line('The task "' . $this->task->task . '" status has been updated to ' . $this->task->status . '.')
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('Thank you for using the app!');
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        $userName = Auth::check() ? Auth::user()->name : 'System';

        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->task,
            'status' => $this->task->status,
            'updated_by' => $userName,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        $userName = Auth::check() ? Auth::user()->name : 'System';

        return new BroadcastMessage([
            'task_id' => $this->task->id,
            'task_name' => $this->task->task,
            'status' => $this->task->status,
            'updated_by' => $userName,
        ]);
    }
}
