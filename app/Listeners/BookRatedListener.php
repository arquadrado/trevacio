<?php

namespace App\Listeners;

use App\Events\BookRated;
use App\Notifications\BookRated as BookRatedNotification;
use App\Models\User;
use Notification;

class BookRatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BookRated  $event
     * @return void
     */
    public function handle(BookRated $event)
    {
        $users = $event->rating->book->users;

        Notification::send($users, new BookRatedNotification($event->rating));
    }
}