@extends('partials.master')

@section('content')
<div class="container login">
    <div class="row login-header">
        <div class="col-xs-12">
            <h1 class="title"><strong><a href="{{ route('home') }}">Kooper</a></strong></h1>
            <h3>Login</h3>
        </div>
    </div>
    <form class="form-horizontal" id="login-form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

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
            <div class="">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="submit-button">
                <button type="submit" class="">
                    Login
                </button>

            </div>
        </div>
        <div class="forgot-password">
            <a class="btn btn-link" href="{{ route('password.request') }}">
                Forgot Your Password?
            </a>
        </div>
        <div class="register">
            <a class="btn btn-link" href="{{ route('register') }}">
                Don't have an account yet? Register here
            </a>
        </div>
    </form>
</div>
@endsection
