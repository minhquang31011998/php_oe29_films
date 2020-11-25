@extends('auth.layouts.master')

@section('title')
    {{ trans('sign_in') }}
@endsection

@section('content')
    <form action="{{ route('loginProcess') }}" method="POST" class="sign__form">
        @csrf
        <a href="index.html" class="sign__logo">
            <img src="{{ asset('bower_components/bower_film/img/LOGO.png') }}" alt="">
        </a>

        @if (isset($error))
            <span class="invalid-feedback sign__text" role="alert">
                <a href=""><strong>{{ $error }}</strong></a>
            </span>
        @enderror

        <div class="sign__group">
            <input id="email" type="email" class="sign__input" name="email" placeholder="{{ trans('email') }}" value="{{ old('email') }}">
        </div>

        <div class="sign__group">
            <input id="password" type="password" class="sign__input" name="password" placeholder="{{ trans('password') }}">
        </div>

        <div class="sign__group sign__group--checkbox">
            <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">{{ trans('remember_me') }}</label>
        </div>

        <button id="login-btn" class="sign__btn" type="submit">{{ trans('sign_in') }}</button>
        <span class="sign__text">{{ trans('no_account') }} <a id="sign-up" href="{{ route('register') }}">{{ trans('sign_up') }}!</a></span>
        <span class="sign__text"><a href="{{ route('forgotPassword') }}">{{ trans('forget_password') }}?</a></span>
    </form>
@endsection
