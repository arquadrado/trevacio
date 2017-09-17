<?php

namespace App\Notifications;

use App\Models\Rating;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookRated extends Notification
{
    use Queueable;

    public $rating;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
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
            'type' => 'book-rated',
            'id' => $this->rating->id,
            'rating' => $this->rating->rating,
            'book' => [
                'id' => $this->rating->book->id,
                'title' => $this->rating->book->title,
                'author' => $this->rating->book->author->name,
            ],
            'updated_at' => $this->rating->book->updated_at->toDateTimeString(),
            'added_by' => Auth::user()
        ];
    }
}
