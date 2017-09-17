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
        $rating = $book->userRating->first();
        
        if (is_null($rating)) {
            $rating = Rating::create([
                'book_id' => $book->id,
                'user_id' => Auth::user()->id
            ,
                'rating' => $rating
            ]);

            return $rating;
        }

        $rating->rating = $rating;
        $rating->save();

        return $rating;
    }

    public function addBookToUserCollection($bookId) {

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

        DB::transaction(function () use ($book) {
            Auth::user()->books()->detach($book->id);
            $book->comments()->delete();
            $book->delete();
        });
    }
}
