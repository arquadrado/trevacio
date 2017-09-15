@extends('partials.master')

@section('content')
<div class="container register">
    <div class="row register-header">
        <div class="col-xs-12">
            <h1 class="title"><strong><a href="{{ route('home') }}">{{ config('app.name', 'Bkooper') }}</a></strong></h1>
            <h3>Register</h3>
        </div>
    </div>
    <form class="form-horizontal" id="register-form" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name">Name</label>

            <div class="col-md-12 input-text">
                <input type="name" name="name" value="{{ old('name') }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email">E-Mail Address</label>

            <div class="col-md-12 input-text">
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password">Password</label>

            <div class="col-md-12 input-text">
                <input type="password" name="password" value="{{ old('password') }}" required autofocus>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group">
            <label for="password-confirm">Confirm Password</label>

            <div class="col-md-12 input-text">
               <input id="password-confirm" type="password" name="password_confirmation" required>

            </div>
        </div>


        <div class="form-group">
            <div class="submit-button">
                <button type="submit" class="">
                    Register
                </button>
            </div>
        </div>
        <div class="login">
            <a class="btn btn-link" href="{{ route('login') }}">
                Already have an account? Login here
            </a>
        </div>
    </form>
</div>
@endsection
