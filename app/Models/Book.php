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

    protected $with = ['author'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
     /*
    }
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
