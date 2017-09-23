<?php

namespace App\Listeners;

use App\Events\NoteAdded;
use App\Notifications\NoteAdded as NoteAddedNotification;
use App\Models\User;
use Notification;

class NoteAddedListener
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
     * @param  NoteAdded  $event
     * @return void
     */
    public function handle(NoteAdded $event)
    {
        $users = $event->note->commentable->user;

        Notification::send($users, new NoteAddedNotification($event->note));
    }
}