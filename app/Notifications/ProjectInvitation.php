<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// app/Notifications/ProjectInvitation.php


class ProjectInvitation extends Notification
{
    use Queueable;

    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $url = route('projects.show', $this->project);

        return (new MailMessage)
            ->subject('Invitation à rejoindre un projet')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Vous avez été invité(e) à rejoindre le projet "' . $this->project->title . '".')
            ->line('Par : ' . $this->project->owner->name)
            ->action('Voir le projet', $url)
            ->line('À bientôt !');
    }

    public function toDatabase($notifiable)
    {
        return [
            'project_id' => $this->project->id,
            'project_title' => $this->project->title,
            'inviter_id' => auth()->id(),
            'inviter_name' => auth()->user()->name,
            'message' => 'Vous avez été invité(e) à rejoindre le projet "' . $this->project->title . '"'
        ];
    }
}
