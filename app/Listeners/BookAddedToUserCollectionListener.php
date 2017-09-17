<?php

namespace App\Listeners;

use App\Events\BookAddedToUserCollection;
use App\Notifications\BookAddedToUserCollection as BookAddedToUserCollectionNotification;
use App\Models\User;
use Notification;

class BookAddedToUserCollectionListener
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
     * @param  BookAddedToUserCollection  $event
     * @return void
     */
    public function handle(BookAddedToUserCollection $event)
    {
        $users = $event->book->users;

        Notification::send($users, new BookAddedToUserCollectionNotification($event->book));
        // Access the order using $event->book...
    }
}