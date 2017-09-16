<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getActivity()
    {
        //$manager = new FeedManager;
        dd(request()->all(), Auth::user());
    }
}
