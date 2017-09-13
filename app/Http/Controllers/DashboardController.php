<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\ReadingSession;
use App\Models\Rating;
use App\Models\Comment;
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

    public function home() {
        dd('hey');
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
            $book->load('readingSessions.notes');
            $reduced[$book->id] = $book;
            return $reduced;
        }, []);

        $library = Book::all()->reduce(function($reduced, $book) {
            $book->load('readingSessions.notes');
            $reduced[$book->id] = $book;
            return $reduced;
        }, []);

        return view('dashboard', [
            'user' => $user,
            'userCollection' => $userCollection,
            'library' => $library
        ]);
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

    public function saveComment()
    {
        $this->validate(request(), [
                    'commentableId' => 'required',
                    'commentableType' => 'required',
                    'comment' => 'required'
                ]);

        if (!Auth::check()) {
            abort(500);
        }

        $commentableTypes = [
            'book' => 'App\Models\Book',
            'session' => 'App\Models\ReadingSession',
        ];

        if (!array_key_exists(request('commentableType'), $commentableTypes)) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $comment = Comment::create([
            'commentable_type' => $commentableTypes[request('commentableType')],
            'commentable_id' => request('commentableId'),
            'body' => request('comment'),
            'user_id' => Auth::user()->id
        ]);

        return response()->json(['message' => 'comment added', 'comment' => $comment], 200);
   


    }

    public function rateBook()
    {
        $this->validate(request(), [
                    'bookId' => 'required',
                    'rating' => 'required'
                ]);

        $book = Book::find(request('bookId'));

        if (is_null($book)) {
            return response()->json([
                    'message' => 'Book not found'
                ], 404);
        }

        $rating = $book->userRating->first();

        if (is_null($rating)) {
            $rating = Rating::create([
                                'book_id' => request('bookId'),
                                'user_id' => Auth::user()->id,
                                'rating' => request('rating')
                            ]);

            return response()->json([
                    'message' => 'added rating'
                ], 200);
        }

        $rating->rating = request('rating');
        $rating->save();

        return response()->json([
                    'message' => 'updated rating'
                ], 200);
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
            $book->load('readingSessions.notes');
            $reduced[$book->id] = $book;
            return $reduced;
        }, []);

        $library = Book::all()->reduce(function ($reduced, $book) {
            $book->load('readingSessions.notes');
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
