<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    protected $fillable = [
        'title',
        'author_id'
    ];

    protected $appends = [
        'in_library',
        'book_stats',
        'book_user_stats',
        'can_delete'
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
        return $this->belongsToMany(User::class, 'user_book');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function allReadingSessions()
    {
        return $this->belongsToMany(ReadingSession::class, 'book_session', 'book_id', 'session_id');
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
    }

    public function getBookStatsAttribute()
    {
        //try {
        $users = $this->users;
        $readingSessions = $this->allReadingSessions()->orderBy('date')->get();

        if ($readingSessions->isEmpty()) {
            return null;
        }

        $data = $readingSessions->reduce(function ($reduced, $session) {
            $reduced['pages'] += $session->end - $session->start;
            if (!array_key_exists($session->date, $reduced['distribution'])) {
                $reduced['distribution'][$session->date] = 0;
            }
            $reduced['distribution'][$session->date] += $session->end - $session->start;
            return $reduced;
        }, [
            'pages' => 0,
            'distribution' => []
        ]);


        $days = Carbon::parse($readingSessions->first()->date)->diffInDays(Carbon::parse($readingSessions->last()->date)) + 1;

        /*} catch (\Exception $e) {
            dd($e);
        }*/
        return [
            'page_average' => round($data['pages'] / $users->count(), 2),
            'page_per_day_average' => $days > 0 ? round($data['pages'] / ($users->count() * $days), 2) : null,
            'distribution' => $data['distribution']
        ];
    }

    public function getBookUserStatsAttribute()
    {
        $readingSessions = $this->readingSessions()->orderBy('date')->get();

        if ($readingSessions->isEmpty()) {
            return null;
        }

        $data = $readingSessions->reduce(function ($reduced, $session) {
            $reduced['pages'] += $session->end - $session->start;
            if (!array_key_exists($session->date, $reduced['distribution'])) {
                $reduced['distribution'][$session->date] = 0;
            }
            $reduced['distribution'][$session->date] += $session->end - $session->start;
            return $reduced;
        }, [
            'pages' => 0,
            'distribution' => []
        ]);

        $days = Carbon::parse($readingSessions->first()->date)->diffInDays(Carbon::parse($readingSessions->last()->date)) + 1;

        return [
            'page_average' => round($data['pages'], 2),
            'page_per_day_average' => $days > 0 ? round($data['pages'] / $days, 2) : null,
            'distribution' => $data['distribution']
        ];
    }

    public function getCanDeleteAttribute()
    {
        return $this->users()->count() == 1 && $this->users()->first()->id == Auth::user()->id;
    }
}
