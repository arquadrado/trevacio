<?php

namespace App\Http\Controllers;

use App\Events\BookAddedToLibrary;
use App\Events\BookAddedToUserCollection;
use App\Events\BookRated;
use App\Events\ReadingSessionAdded;
use App\Http\Controllers\AjaxController;
use App\Models\Book;
use App\Support\BookManager;
use Auth;
use Illuminate\Http\Request;
use Notification;

class BookController extends AjaxController
{
    protected $manager;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BookManager $manager)
    {
        $this->manager = $manager;
    }

    public function saveReadingSession()
    {
        $this->validate(request(), ['session' => 'required', 'bookId' => 'required']);

        $user = Auth::user();
        $book = Book::find(request('bookId'));

        if (is_null($user) || is_null($book)) {
            return $this->sendJsonResponse(['message' => 'Critical error'], 404);
        }

        
        try {
            $readingSession = $this->manager->saveReadingSession($book, request('session'));
        } catch (\Exception $e) {
            return $this->sendJsonResponse(['message' => $e->getMessage()], 404);
        }

        event(new ReadingSessionAdded($readingSession));

        return $this->sendJsonResponse(['message' => 'Session Saved', 'session' => $readingSession], 200);

    }

    public function rateBook()
    {
        $this->validate(request(), [
            'bookId' => 'required',
            'rating' => 'required'
        ]);

        $book = Book::find(request('bookId'));

        if (is_null($book)) {
            return $this->sendJsonResponse([
                'message' => 'Book not found'
            ], 404);
        }

        try {
            $rating = $this->manager->rateBook($book, request('rating'));
        } catch (\Exception $e) {
            return $this->sendJsonResponse([
                'message' => $e->getMessage()
            ], 500);
        }

        event(new BookRated($rating));

        return $this->sendJsonResponse([
            'message' => 'book rated'
        ], 200);
    }

    public function addToUserCollection()
    {
        $this->validate(request(), ['book' => 'required']);

        $book = Book::find(request('book')['id']);

        if (is_null($book)) {
            return $this->sendJsonResponse(['message' => 'Book not found'], 404);
        }
        
        $owned = Auth::user()->books()->where('book_id', $book->id)->first();
        
        if ($owned) {
            return $this->sendJsonResponse([
                'message' => 'You already have this book in your library. Do you want to open it?',
                'book' => $book
            ], 202);
        }
        try {
            $this->manager->addBookToUserCollection($book->id);
        } catch (\Exception $e) {
            return $this->sendJsonResponse(['message' => $e->getMessage()], 500);
        }

        event(new BookAddedToUserCollection($book));

        return $this->sendJsonResponse([
            'message' => 'Book added',
            'book' => $book
        ], 200);
    }

    public function saveBook()
    {
        $this->validate(request(), [
            'title' => 'required',
            'author' => 'required',
        ]);

        $books = Book::where('title', request('title'))->get();

        if (request('force') || $books->isEmpty()) {
            try {
                $book = $this->manager->saveBook(request('title'), request('author')); 
            } catch (\Exception $e) {
                return $this->sendJsonResponse([
                    'message' => 'An error occurred',
                    'exception' => $e->getMessage()
                ], 500);
            }

            event(new BookAddedToLibrary($book));

            return $this->sendJsonResponse([
                'message' => 'Book added',
                'book' => $book
            ], 200);
        }

        return $this->sendJsonResponse([
            'message' => 'Found somebooks with the same title',
            'books' => $books
        ], 201);
    }

    public function deleteBook()
    {
        $this->validate(request(), ['bookId' => 'required']);

        $book = Book::find(request('bookId'));

        if (is_null($book)) {
            return $this->sendJsonResponse(['message' => 'Book not found'], 404);
        }

        if (count($book->users) > 1 || $book->users()->first()->id != Auth::user()->id) {
            return $this->sendJsonResponse(['message' => 'You do not have permission to delete this book'], 403);
        }

        try {
            $this->manager->deleteBook($book->id);
        } catch (\Exception $e) {
            return $this->sendJsonResponse(['message' => $e->getMessage()], $e->getStatus());
        }
        return $this->sendJsonResponse(['message' => 'Book deleted'], 200);
    }
}
