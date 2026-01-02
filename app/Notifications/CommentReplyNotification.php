<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Guide;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Comment $reply,
        public Comment $parentComment,
        public Guide $guide
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('guides.show', ['guide' => $this->guide->slug]).'#comment-'.$this->reply->id;

        return (new MailMessage)
            ->subject('New Reply to Your Comment - RTFM.guide')
            ->view('emails.comment-reply', [
                'url' => $url,
                'replyUserName' => $this->reply->user->name,
                'replyContent' => $this->reply->content,
                'guideTitle' => $this->guide->title,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'reply_user_id' => $this->reply->user_id,
            'reply_user_name' => $this->reply->user->name,
            'parent_comment_id' => $this->parentComment->id,
            'guide_id' => $this->guide->id,
            'guide_slug' => $this->guide->slug,
            'guide_title' => $this->guide->title,
        ];
    }
}
