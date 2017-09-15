@extends('partials.master')

@section('content')
    <div class="welcome">
        <div class="greetings">
            <h1><strong>Welcome to <a href="{{ route('dashboard') }}">{{ config('app.name', 'Bkooper') }}</a></strong></h1>
            <h4>where you koop your beeks...</h4>
            <h3 class="keep-books"><strong><a href="{{ route('dashboard') }}">keep your books</a></strong></h3><h4 class="i-mean">, I mean</h4>
        </div>
    </div>
@endsection