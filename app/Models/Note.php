<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Note extends Model
{

    protected $fillable = [
        'user_id',
        'commentable_id',
        'commentable_type',
        'body',
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

    protected $with = ['user'];

    public function session()
    {
        return $this->belongsTo(ReadingSession::class);
    }
    
     /*
    }
    ==========================================================================
       Mutators
    ==========================================================================
    */
}
