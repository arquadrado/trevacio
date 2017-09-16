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

    public function getUserFeed($take = 10, $skip = 0)
    {
        $user = Auth::user();

        if (is_null($user)) {
            return null;
        }

        $books = $user->books;
        $sessions = $user->readingSessions;
        $ratings = $user->ratings;
        $comments = $user->comments;
        $feed = (new Collection)
                    ->merge($books)
                    ->merge($sessions)
                    ->merge($ratings)
                    ->merge($comments)
                    ->sortBy(function($item) {
                        return $item->updated_at;
                    })
                    ->reverse()
                    ->slice($skip, $take)
                    ->reduce(function ($reduced, $item) {
                        try {
                            array_push($reduced, $this->__resolveItem($item));
                        } catch (\Exception $e) {
                            dd($e, $item);
                        }

                        return $reduced;
                    }, []);
        
        return $feed;
    }

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
                    'id' => $item->id
                ];

            case 'App\Models\ReadingSession':
                return [
                    'type' => 'session',
                    'id' => $item->id,
                    'bookId' => $item->book->id
                ]; 

            case 'App\Models\Rating':
                return [
                    'type' => 'rating',
                    'id' => $item->id,
                    'bookId' => $item->book->id
                ];
            
            case 'App\Models\Comment':
                if (get_class($item->commentable) == 'App\Models\ReadingSession') {
                    return [
                        'type' => 'note',
                        'id' => $item->id,
                        'sessionId' => $item->commentable->id,
                        'bookId' => $item->commentable->book->id 
                    ];
                }
                return [
                    'type' => 'comment',
                    'id' => $item->id,
                    'bookId' => $item->commentable->id,
                ];
        }
    }
}
