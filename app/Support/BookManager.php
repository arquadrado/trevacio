<?php

namespace App\Support;

use App\Models\Author;
use App\Models\Book;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\ReadingSession;
use Auth;
use Illuminate\Support\Collection;
use DB;

class BookManager
{
    protected $commentableTypes = [
        'book' => 'App\Models\Book',
        'session' => 'App\Models\ReadingSession',
    ];

    public function saveComment($commentData)
    {
        if (!array_key_exists($commentData['commentableType'], $this->commentableTypes)) {
            return null;
        }

        $comment = Comment::create([
            'commentable_type' => $this->commentableTypes[$commentData['commentableType']],
            'commentable_id' => $commentData['commentableId'],
            'body' => $commentData['comment'],
            'user_id' => Auth::user()->id
        ]);

        return $comment;
    }

    public function saveReadingSession(Book $book, $session)
    {
        if (is_null($book)) {
            throw new \Exception('Cannot add a session to a book with a null id');
        }        
        $readingSession = null;

        DB::transaction(function () use ($book, $session, &$readingSession) {
            $readingSession = ReadingSession::create(
                array_merge($session, ['book_id' => $book->id, 'user_id' => Auth::user()->id])
            );
        });

        return $readingSession;

    }

    public function rateBook(Book $book, $rating)
    {
        $userRating = $book->userRating->first();
        
        if (is_null($userRating)) {
            $userRating = Rating::create([
                'book_id' => $book->id,
                'user_id' => Auth::user()->id,
                'rating' => $rating
            ]);
                
            return $userRating;
        }
        $userRating->rating = $rating;
        $userRating->save();

        return $userRating;
    }

    public function addBookToUserCollection($bookId)
    {

        if (is_null($bookId)) {
            throw new \Exception('Cannot add a book with a null id');
        }

        $user = Auth::user()->books()->attach($bookId);
        
    }

    public function saveBook($title, $authorName)
    {
        if (is_null($title) || is_null($authorName)) {
            return null;
        }

        $book = null;

        DB::transaction(function () use ($title, $authorName, &$book) {
            $author = Author::firstOrCreate([
                'name' => $authorName
            ]);
            $book = Book::create([
                'title' => $title,
                'author_id' => $author->id
            ]);

            Auth::user()->books()->attach($book->id);
        });

        return $book;
    }

    public function deleteBook($id)
    {
        if (is_null($id)) {
          throw new \Exception('Invalid id. Cannot delete the book with that kind of attitude');
        }
          
        $book = Book::find($id);
        
        if (is_null($book)) {
          throw new \Exception('Book not found');
        }

        DB::transaction(function () use ($book) {
            Auth::user()->books()->detach($book->id);
            $book->comments()->delete();
            $book->delete();
        });
    }
}
