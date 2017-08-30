<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\ReadingSession;
use Illuminate\Http\Request;
use Auth;
use DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = Auth::user();

        $userCollection = $user->books->reduce(function($reduced, $book) {
            $reduced[$book->id] = $book;
            return $reduced;
        }, []);

        $library = Book::all()->reduce(function($reduced, $book) {
            $reduced[$book->id] = $book;
            return $reduced;
        }, []);

        return view('dashboard', [
            'user' => $user,
            'userCollection' => $userCollection,
            'library' => $library
        ]);
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

    public function addToUserCollection()
    {
        $this->validate(request(), ['book' => 'required']);

        $book = Book::find(request('book')['id']);

        $owned = Auth::user()->books()->where('book_id', $book->id)->first();

        if ($owned) {
            return response()->json([
                'message' => 'You already have this book in your library. Do you want to open it?',
                'book' => $book
                ], 202);
        }

        if ($book) {
            Auth::user()->books()->attach($book->id);

            return response()->json([
                    'message' => 'Book added',
                    'book' => $book
                ], 200);
        }

        return response()->json(['message' => 'Book not found'], 404);
    }

    public function removeFromUserCollection()
    {
        $this->validate(request(), ['bookId' => 'required']);
        $book = Book::find(request('bookId'));

        $owned = Auth::user()->books()->where('book_id', $book->id)->first();

        if ($owned) {
            Auth::user()->books()->detach($book->id);

            return response()->json([
                'message' => 'Book removed from collection',
                'book' => $book
                ], 200);
        }

        return response()->json([
                'message' => 'You can remove a book that is not in your collection.',
                'book' => $book
                ], 202);
    }

    public function saveBook()
    {
        $data = array_except(request()->all(), ['_token']);

        $this->validate(request(), [
                                    'title' => 'required',
                                    'author' => 'required',
                                ]);

        $books = Book::where('title', $data['title'])->get();

        if ((isset($data['force']) && $data['force']) || $books->isEmpty()) {
            $bookId = null;
            try {
                DB::transaction(function () use ($data, &$bookId) {
                    $author = Author::firstOrCreate([
                                    'name' => $data['author'],
                                    'country' => 'Xis',
                                    'birthday' => '1988-07-13'
                                ]);
                    $book = Book::create([
                                'title' => $data['title'],
                                'author_id' => $author->id
                            ]);

                    $bookId = $book->id;

                    Auth::user()->books()->attach($book->id);
                });
            } catch (\Exception $e) {
                dd($e);
            }

            $book = Book::find($bookId);

            return response()->json([
                    'message' => 'Book added',
                    'book' => $book
                ], 200);
        }

        return response()->json([
                                'message' => 'Found somebooks with the same title',
                                'books' => $books
                            ], 201);
    }

    public function deleteBook()
    {
        $this->validate(request(), ['bookId' => 'required']);

        $book = Book::find(request('bookId'));

        if (is_null($book)) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if (count($book->users) > 1 || $book->users()->first()->id != Auth::user()->id) {
            return response()->json(['message' => 'You do not have permission to delete this book'], 403);
        }

        try {
            DB::transaction(function () use ($book) {
                Auth::user()->books()->detach($book->id);
                $book->delete();
            });

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getStatus());
        }

        return response()->json(['message' => 'Book deleted'], 200);
    }

    public function updateLibrary()
    {
        $user = Auth::user();

        $userCollection = $user->books->reduce(function ($reduced, $book) {
            $reduced[$book->id] = $book;
            return $reduced;
        }, []);

        $library = Book::all()->reduce(function ($reduced, $book) {
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
                $readingSession->delete();
            });

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Session deleted'], 200);
    }
}
