<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Notifications\CommentAdded as CommentAddedNotification;
use App\Models\User;
use Notification;

class CommentAddedListener
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
     * @param  CommentAdded  $event
     * @return void
     */
    public function handle(CommentAdded $event)
    {
        $users = $event->comment->commentable->users;

        Notification::send($users, new CommentAddedNotification($event->comment));
    }
}