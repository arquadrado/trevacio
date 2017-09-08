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
        'friendly_name',
        'longest_session'
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

    public function readingSessions()
    {
        return $this->hasMany(ReadingSession::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
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

    public function getLongestSessionAttribute()
    {
        return $this->readingSessions->reduce(function ($reduced, $session) {
            if (!array_key_exists($session->date, $reduced['distribution'])) {
                $reduced['distribution'][$session->date] = 0;
            }
            $reduced['distribution'][$session->date] += $session->end - $session->start;
            if ($reduced['distribution'][$session->date] > $reduced['count']) {
                $reduced['count'] = $reduced['distribution'][$session->date];
            }
            
            return $reduced;
        }, ['distribution' => [], 'count' => 0])['count'];
    }
}
