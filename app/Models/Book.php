<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Book extends Model
{

    protected $fillable = [
        'title',
        'author_id'
    ];

    protected $appends = [
        'in_library'
    ];

    /*
     *
     * Relations
     *
     *
     *
     **/

    protected $with = ['author', 'readingSessions'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function readingSessions()
    {
        return $this->belongsToMany(ReadingSession::class, 'book_session', 'book_id', 'session_id')
                    ->join('user_session', 'user_session.session_id', '=', 'book_session.session_id')
                    ->where('user_id', Auth::user()->id);
    }

     /*
    ==========================================================================
       Mutators
    ==========================================================================
    */

    public function getInLibraryAttribute()
    {
        return !is_null(Auth::user()->books()->where('book_id', $this->id)->first());
        //return Auth::user()->books()->where('book_id', $this->id)->first();
    }
}
