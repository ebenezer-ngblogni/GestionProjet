<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Notification par email et stockage en base de données
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nouvelle tâche assignée')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Une nouvelle tâche vous a été assignée : ' . $this->task->title)
                    ->action('Voir la tâche', url('/projects/' . $this->task->project_id . '/tasks/' . $this->task->id))
                    ->line('Merci de votre engagement !');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'project_id' => $this->task->project_id,
            'message' => 'Une nouvelle tâche vous a été assignée.',
        ];
    }
}
