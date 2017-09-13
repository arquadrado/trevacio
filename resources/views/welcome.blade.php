@extends('partials.master')

@section('content')
    <div class="welcome">
        <div class="greetings">
            <h1><strong>Welcome to <a href="">Kooper</a></strong></h1>
            <h3>where you koop your beeks...</h3>
            <h3><strong><a href="{{ route('dashboard') }}">keep your books</a></strong></h3>
            <h4>I mean</h4>
        </div>
    </div>
@endsection