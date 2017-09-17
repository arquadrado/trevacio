<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AjaxController;
use App\Support\FeedManager;
use Auth;

class FeedController extends AjaxController
{
    protected $manager;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FeedManager $manager)
    {
        $this->manager = $manager;
    }

    public function getUserActivity()
    {
        $user = Auth::user();

        if (is_null(Auth::user())) {
            return $this->sendJsonResponse(['message' => 'User not found'], 404);
        }

        return $this->sendJsonResponse([
            'message' => 'All ok',
            'feed' => $this->manager->getUserFeed(
                request()->query('skip'),
                request()->query('take')
            )
        ], 200);
    }
}
