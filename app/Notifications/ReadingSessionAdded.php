<?php

namespace App\Notifications;

use App\Models\ReadingSession;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReadingSessionAdded extends Notification
{
    use Queueable;

    public $readingSession;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ReadingSession $readingSession)
    {
        $this->readingSession = $readingSession;
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
            'type' => 'session-added',
            'id' => $this->readingSession->id,
            'pages' => $this->readingSession->end - $this->readingSession->start,
            'book' => [
                'id' => $this->readingSession->book->id,
                'title' => $this->readingSession->book->title,
                'author' => $this->readingSession->book->author->name,
            ],
            'updated_at' => $this->readingSession->book->updated_at->toDateTimeString(),
            'added_by' => Auth::user()
        ];
    }
}
