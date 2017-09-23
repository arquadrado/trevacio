<?php

namespace App\Notifications;

use App\Models\Comment;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NoteAdded extends Notification
{
    use Queueable;

    public $note;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $note)
    {
        $this->note = $note;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'note',
            'id' => $this->note->id,
            'book' => [
                'id' => $this->note->commentable->book->id,
                'title' => $this->note->commentable->book->title,
                'author' => $this->note->commentable->book->author->name,
            ],
            'session' => [
                'id' => $this->note->commentable->id,
                'date' => $this->note->commentable->date,
                'pages' => $this->note->commentable->end - $this->note->commentable->start,
            ],
            'updated_at' => $this->note->updated_at->toDateTimeString(),
            'edited' => $this->note->updated_at !== $this->note->created_at,
            'added_by' => Auth::user()
        ];
    }
}
