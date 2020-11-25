@extends('auth.layouts.master')

@section('title')
    {{ trans('forget_password') }}
@endsection

@section('content')
    <form action="{{ route('sendMailForgotPassword') }}" method="POST" class="sign__form">
        @csrf
        <a href="{{ route('backend.home') }}" class="sign__logo">
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

        <button id="login-btn" class="sign__btn" type="submit">{{ trans('send') }}</button>
    </form>
@endsection
