<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class PostCreateNotification extends Notification
{
    use Queueable;
   
    public $post;
    /**
     * Create a new notification instance.
     */

    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Post Created')
            ->line('A new post has been created: ' . $this->post->title)
            ->action('view Post', url('/posts/' . $this->post->id));
    }

    public function toDatabase($notifiable)
    {
        return [
            'post_id'   => $this->post->id,
            'title'     => $this->post->title,
            'message'   => 'A new post has been created by ' . $this->post->user->name,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
