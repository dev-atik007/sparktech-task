<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;

class PostLikeNotification extends Notification
{
    use Queueable;

    public $post;
    public $liker_id;
    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post, $liker_id)
    {
        $this->post = $post;
        $this->liker_id = $liker_id;
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
                    ->subject('Your Post was liked!')
                    ->line('Your post "' . $this->post->title . '" was liked by user id: ' . $this->liker_id)
                    ->action('View post', url('/posts/' . $this->post->id));
    }

    public function toDatabase($notifiable)
    {
        return [
            'post_id'  => $this->post->id,
            'title'    => $this->post->title,
            'message'  => 'Your post "' . $this->post->title . '" was liked by user id: ' . $this->liker_id
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
