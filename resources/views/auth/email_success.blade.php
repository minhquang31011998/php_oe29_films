@extends('auth.layouts.master')

@section('title')
    {{ trans('forget_password') }}
@endsection

@section('content')
    <form action="" method="" class="sign__form">
        <a href="index.html" class="sign__logo">
            <img src="{{ asset('bower_components/bower_film/img/LOGO.png') }}" alt="">
        </a>
        <span class="invalid-feedback sign__text" role="alert">
            <strong>{{ trans('sent_mail') }}</strong>
        </span>
        <span class="sign__text"><a href="{{ route('login') }}">{{ trans('sign_in') }}</a></span>
    </form>
@endsection
