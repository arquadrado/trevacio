<?php

namespace App\Listeners;

use App\Events\BookAddedToLibrary;
use App\Notifications\BookAddedToLibrary as BookAddedToLibraryNotification;
use App\Models\User;
use Notification;

class BookAddedToLibraryListener
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
     * @param  BookAddedToLibrary  $event
     * @return void
     */
    public function handle(BookAddedToLibrary $event)
    {
        $users = User::all();

        Notification::send($users, new BookAddedToLibraryNotification($event->book));
        // Access the order using $event->book...
    }
}