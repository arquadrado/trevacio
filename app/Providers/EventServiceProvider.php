<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\BookAddedToLibrary' => [
            'App\Listeners\BookAddedToLibraryListener',
        ],
        'App\Events\BookAddedToUserCollection' => [
            'App\Listeners\BookAddedToUserCollectionListener',
        ],
        'App\Events\BookRated' => [
            'App\Listeners\BookRatedListener',
        ],
        'App\Events\ReadingSessionAdded' => [
            'App\Listeners\ReadingSessionAddedListener',
        ],
        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
