<?php

namespace App\Models;

use Carbon\Carbon;
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
        'longest_session',
        'stats'
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

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function colorSchemes()
    {
        return $this->hasMany(ColorScheme::class)->orderBy('order');
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

    public function getStatsAttribute()
    {
        $readingSessions = $this->readingSessions()->orderBy('date', 'desc')->get();

        if ($readingSessions->isEmpty()) {
            return null;
        }

        $data = $readingSessions->reduce(function ($reduced, $session) {
            $reduced['pages'] += $session->end - $session->start;
            if (!array_key_exists($session->date, $reduced['distribution'])) {
                $reduced['distribution'][$session->date] = [
                   'pages' => 0
                ];
            }
            $reduced['distribution'][$session->date]['pages'] += $session->end - $session->start;
            return $reduced;
        }, [
            'pages' => 0,
            'distribution' => []
        ]);

        $days = Carbon::parse($readingSessions->first()->date)->diffInDays(Carbon::parse($readingSessions->last()->date)) + 1;

        return [
            'session_count' => $readingSessions->count(),
            'timespan' => $days,
            'page_average' => round($data['pages'], 2),
            'page_per_day_average' => $days > 0 ? round($data['pages'] / $days, 2) : null,
            'distribution' => $data['distribution']
        ];
    }
}
