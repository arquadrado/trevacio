<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Comment extends Model
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function commentable()
    {
        return $this->morphTo();
    }

     /*
    }
    ==========================================================================
       Mutators
    ==========================================================================
    */
}
