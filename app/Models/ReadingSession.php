<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ReadingSession extends Model
{

    protected $fillable = [
        'user_id',
        'book_id',
        'start',
        'end',
        'date'
    ];

    protected $appends = [
    ];

    /*
     *
     * Relations
     *
     *
     *
     **/

    protected $with = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

     /*
    }
    ==========================================================================
       Mutators
    ==========================================================================
    */
}
