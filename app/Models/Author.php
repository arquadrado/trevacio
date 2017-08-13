<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{

    protected $fillable = [
        'name',
        'country',
        'birthday'
    ];

    /*
     *
     * Relations
     *
     *
     *
     **/

    public function books()
    {
        return $this->hasMany(Book::class);
    }
     /*
    }
    ==========================================================================
       Mutators
    ==========================================================================
    */
}
