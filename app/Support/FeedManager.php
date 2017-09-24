<?php

namespace App\Support;

use App\Models\Book;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\ReadingSession;
use Auth;
use Illuminate\Support\Collection;

class FeedManager
{
    public function __construct()
    {


    }

    public function getUserFeed($skip = 0, $take = 10)
    {
        $user = Auth::user();
        
        if (is_null($user)) {
            return null;
        }
        
        $allEntries = $user
                    ->notifications;

        $totalEntriesCount = $allEntries->count();

        $entries = $allEntries
                    ->slice((int)$skip, (int)$take)
                    ->map(function($notification) {
                        return $notification->data;
                    })
                    ->sortByDesc('updated_at')
                    ->values()
                    ->all();

        //dd($entries);

        return [
            'entries' => $entries,
            'hasMoreEntries' => $totalEntriesCount > $skip + $take
        ];
    }
    
    // public function getUserFeed($skip = 0, $take = 10)
    // {
    //     $user = Auth::user();

    //     if (is_null($user)) {
    //         return null;
    //     }
    //     $books = $user->books;
    //     $sessions = $user->readingSessions;
    //     $ratings = $user->ratings;
    //     $comments = $user->comments;
    //     $feed = (new Collection)
    //                 ->merge($books)
    //                 ->merge($sessions)
    //                 ->merge($ratings)
    //                 ->merge($comments)
    //                 ->sortBy(function($item) {
    //                     return $item->updated_at;
    //                 })
    //                 ->reverse()
    //                 ->slice($skip, $take)
    //                 ->reduce(function ($reduced, $item) {
    //                     try {
    //                         array_push($reduced, $this->__resolveItem($item));
    //                     } catch (\Exception $e) {
    //                         dd($e, $item);
    //                     }

    //                     return $reduced;
    //                 }, []);
        
    //     return $feed;
    // }

    public function getGeneralFeed()
    {
        return null;
    }

    private function __resolveItem($item)
    {
        switch (get_class($item)) {
            case 'App\Models\Book':
                return [
                    'type' => 'book',
                    'id' => $item->id,
                    'title' => $item->title,
                    'author' => $item->author->name,
                    'updated_at' => $item->updated_at->toDateTimeString()
                ];

            case 'App\Models\ReadingSession':
                return [
                    'type' => 'session',
                    'id' => $item->id,
                    'updated_at' => $item->updated_at->toDateTimeString() ,
                    'pages' => $item->end - $item->start,
                    'book' => [
                        'id' => $item->book->id,
                        'title' => $item->book->title,
                        'author' => $item->book->author->name
                    ]
                ]; 

            case 'App\Models\Rating':
                return [
                    'type' => 'rating',
                    'id' => $item->id,
                    'rating' => $item->rating,
                    'updated_at' => $item->updated_at->toDateTimeString(),
                    'book' => [
                        'id' => $item->book->id,
                        'title' => $item->book->title,
                        'author' => $item->book->author->name 
                    ]
                ];
            
            case 'App\Models\Comment':
                if (get_class($item->commentable) == 'App\Models\ReadingSession') {
                    return [
                        'type' => 'note',
                        'id' => $item->id,
                        'updated_at' => $item->updated_at->toDateTimeString(),
                        'session' => [
                            'id' => $item->commentable->id,
                            'date' => $item->commentable->date,
                            'pages' => $item->commentable->end - $item->commentable->start,
                        ],
                        'book' => [
                            'id' => $item->commentable->book->id,
                            'title' => $item->commentable->book->title,
                            'author' => $item->commentable->book->author->name
                        ]       
                    ];
                }
                return [
                    'type' => 'comment',
                    'id' => $item->id,
                    'updated_at' => $item->updated_at->toDateTimeString(),
                    'book' => [
                        'id' => $item->commentable->id,
                        'title' => $item->commentable->title,
                        'author' => $item->commentable->author->name
                    ] 
                ];
        }
    }
}
