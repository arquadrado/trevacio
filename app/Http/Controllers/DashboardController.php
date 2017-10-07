<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\ReadingSession;
use App\Models\Rating;
use App\Models\Comment;
use App\Models\ColorScheme;
use Illuminate\Http\Request;
use Auth;
use DB;

class DashboardController extends Controller
{

    protected $feedManager;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = Auth::user();
        $user->load('colorSchemes');

        $userCollection = $user->books->reduce(function($reduced, $book) {
            $book->load([
                'author',
                'readingSessions.notes',
                'userRating',
                'ratings',
                'comments'
            ]);
            $reduced[$book->id] = $book;
            return $reduced;
        }, []);

        $library = Book::all()->reduce(function($reduced, $book) {
            $book->load([
                'author',
                'readingSessions.notes',
                'userRating',
                'ratings',
                'comments'
            ]); 
            $reduced[$book->id] = $book;
            return $reduced;
        }, []);

        $authors = Author::all()->reduce(function ($reduced, $author) {
            $author->load('books');
            $reduced[$author->id] = $author;
            return $reduced;
        }, []);

        return view('dashboard', [
            'user' => $user,
            'userCollection' => $userCollection,
            'library' => $library,
            'authors' => $authors
        ]);
    }
    
    public function setDefaultColorScheme()
    {
        $this->validate(request(), [
          'value' => 'required',
        ]);
        
        $user = Auth::user();
        
        if (is_null($user)) {
          return response()->json(['message' => 'An error has occurred'], 500);
        }
        
        $user->default_color_scheme = request('value');
        $user->save();
        
        return response()->json([
            'message' => 'Color scheme set set',
        ], 200);
    }

    public function updateColorScheme()
    {
        $this->validate(request(), [
                    'schemeId' => 'required',
                    'details' => 'required',
                    'background' => 'required',
                ]);

        $colorScheme = ColorScheme::find(request('schemeId'));

        if (is_null($colorScheme)) {
            return response()->json(['message' => 'An error has occurred'], 500);
        }

        $colorScheme->details = request('details');
        $colorScheme->background = request('background');

        if (request('font')) {
            $colorScheme->font = request('font');

        }
        $colorScheme->save();

        return response()->json([
            'message' => 'Color scheme updated',
            'scheme' => $colorScheme
        ], 200);
    }

    public function saveColorScheme()
    {
        $this->validate(request(), [
                    'details' => 'required',
                    'background' => 'required',
                ]);

        try {
            $colorScheme = ColorScheme::create([
                'details' => request('details'),
                'background' => request('background'),
                'font' => request('font'),
                'user_id' => Auth::user()->id
            ]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['message' => 'An error has occurred'], 500);
        }

        return response()->json([
            'message' => 'Color scheme saved',
            'scheme' => $colorScheme
        ], 200);
    }

    public function deleteColorScheme()
    {
        $this->validate(request(), [
                    'schemeId' => 'required',
                ]);

        $colorScheme = ColorScheme::find(request('schemeId'));

        if (is_null($colorScheme)) {
            return response()->json(['message' => 'An error has occurred'], 500);
        }

        return response()->json(['message' => 'Color scheme deleted'], 200);
    }

    public function updateComment()
    {
        $this->validate(request(), [
                    'commentId' => 'required',
                    'comment' => 'required'
                ]);

        if (!Auth::check()) {
            abort(500);
        }

        $comment = Comment::find(request('commentId'));

        if (is_null($comment)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        if (Auth::user()->id !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->body = request('comment');
        $comment->save();

        return response()->json(['message' => 'comment updated'], 200);



    }

    public function updateUserInfo()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    public function getBook()
    {
        $this->validate(request(), ['title' => 'required']);

        $books = Book::where('title', request('title'))->get();

        if (is_null($books) || $books->isEmpty()) {

            $authors = Author::where('name', request('title'))->get();

            $books = $authors->reduce(function ($reduced, $author) {
                if (is_null($reduced)) {
                    $reduced = $author->books;
                    return $reduced;
                }

                $reduced->merge($author->books);

                return $reduced;
            }, null);

            if (!is_null($books) && !$books->isEmpty()) {
                return response()->json([
                    'message' => 'Found some books!',
                    'books' => $books
                ], 201);
            }

            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        if (count($books) == 1) {
            return response()->json([
                    'message' => 'Here it is!',
                    'book' => $books->first()
                ], 200);
        }

        return response()->json([
                    'message' => 'There some books with that title!',
                    'books' => $books
                ], 201);
    }

    public function removeFromUserCollection()
    {
        $this->validate(request(), ['bookId' => 'required']);
        $book = Book::find(request('bookId'));
        $user = Auth::user();
        $owned = $user->books()->where('book_id', $book->id)->first();


        if ($owned) {
            $user->books()->detach($book->id);
            $user->ratings()->where('book_id', $book->id)->delete();

            return response()->json([
                'message' => 'Book removed from collection',
                'book' => $book
                ], 200);
        }

        return response()->json([
                'message' => 'You cannot remove a book that is not in your collection.',
                'book' => $book
                ], 202);
    }

    public function updateLibrary()
    {
        $user = Auth::user();

        $userCollection = $user->books->reduce(function ($reduced, $book) {
            $book->load([
                'author',
                'readingSessions.notes',
                'userRating',
                'ratings',
                'comments'
            ]);
            $reduced[$book->id] = $book;
            return $reduced;
        }, []);

        $library = Book::all()->reduce(function ($reduced, $book) {
            $book->load([
                'author',
                'readingSessions.notes',
                'userRating',
                'ratings',
                'comments'
            ]);
            $reduced[$book->id] = $book;
            return $reduced;
        }, []);

        return response()->json([
            'message' => 'All good',
            'user' => $user,
            'userCollection' => $userCollection,
            'library' => $library
        ], 200);
    }

    public function saveReadingSession()
    {
        $this->validate(request(), ['session' => 'required', 'bookId' => 'required']);

        $user = Auth::user();
        $book = Book::find(request('bookId'));

        if (is_null($user) || is_null($book)) {
            return response()->json(['message' => 'Critical error'], 404);
        }

        $readingSession = null;
        try {
            DB::transaction(function () use ($book, $user, &$readingSession) {
                $readingSession = ReadingSession::create(array_merge(request('session'), ['book_id' => $book->id, 'user_id' => $user->id]));
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }

        return response()->json(['message' => 'Session Saved', 'session' => $readingSession], 200);

    }

    public function deleteReadingSession()
    {
        $this->validate(request(), ['sessionId' => 'required']);

        try {
            DB::transaction(function () {
                $readingSession = ReadingSession::find(request('sessionId'));
                $readingSession->notes()->delete();
                $readingSession->delete();
            });

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Session deleted'], 200);
    }
}
