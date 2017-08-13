<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'friendly_name'
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
        return $this->belongsToMany(Book::class, 'user_book');
    }


    /*
     *
     * Mutators
     *
     *
     *
     **/

    public function getFriendlyNameAttribute()
    {
        return explode(' ', $this->name)[0];
    }
}
