<?php

namespace App\Listeners;

use App\Events\ReadingSessionAdded;
use App\Notifications\ReadingSessionAdded as ReadingSessionAddedNotification;
use App\Models\User;
use Notification;

class ReadingSessionAddedListener
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
    public function handle(ReadingSessionAdded $event)
    {
        $users = $event->readingSession->user;

        Notification::send($users, new ReadingSessionAddedNotification($event->readingSession));
    }
}