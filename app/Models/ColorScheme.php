<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColorScheme extends Model
{

    protected $fillable = [
        'details',
        'background',
        'font',
        'user_id'
    ];

    /*
     *
     * Relations
     *
     *
     *
     **/

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     /*
    }
    ==========================================================================
       Mutators
    ==========================================================================
    */
}
