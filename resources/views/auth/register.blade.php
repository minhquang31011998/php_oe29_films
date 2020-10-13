@extends('auth.layouts.master')

@section('title')
    {{ trans('sign_in') }}
@endsection

@section('content')
    <form method="POST" action="{{ route('registerProcess') }}" class="sign__form">
        @csrf
        <a href="index.html" class="sign__logo">
            <img src="{{ asset('bower_components/bower_film/img/LOGO.png') }}" alt="">
        </a>

        @error ('name')
            <span class="invalid-feedback sign__text" role="alert">
                <a href=""><strong>{{ $message }}</strong></a>
            </span>
        @enderror
        <div class="sign__group">
            <input type="text" class="sign__input" placeholder="{{ trans('name') }}" name="name" value="{{ old('name') }}">
        </div>

        @error ('email')
            <span class="invalid-feedback sign__text" role="alert">
                <a href=""><strong>{{ $message }}</strong></a>
            </span>
        @enderror
        <div class="sign__group">
            <input type="email" class="sign__input" placeholder="{{ trans('email') }}" name="email" value="{{ old('email') }}">
        </div>

        @error ('phone')
            <span class="invalid-feedback sign__text" role="alert" name="phone">
                <a href=""><strong>{{ $message }}</strong></a>
            </span>
        @enderror
        <div class="sign__group">
            <input type="text" class="sign__input" placeholder="{{ trans('phone') }}" name="phone" value="{{ old('phone') }}">
        </div>

        @error ('password')
            <span class="invalid-feedback sign__text" role="alert">
                <a href=""><strong>{{ $message }}</strong></a>
            </span>
        @enderror
        <div class="sign__group">
            <input type="password" class="sign__input" placeholder="{{ trans('password') }}" name="password" required autocomplete="new-password">
        </div>

        <div class="sign__group">
            <input type="password" class="sign__input" placeholder="{{ trans('password_confirm') }}" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button class="sign__btn" type="submit">{{ trans('sign_up') }}</button>

        <span class="sign__text">{{ trans('have_account') }} <a href="{{ route('login') }}">{{ trans('sign_in') }}!</a></span>
    </form>
@endsection
