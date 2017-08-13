<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
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
        return view('dashboard');
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

    public function addToLibrary()
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
}
