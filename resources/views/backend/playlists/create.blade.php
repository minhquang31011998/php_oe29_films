@extends('backend.layouts.master')

@section('title'){{ trans('add_playlist') }}@endsection

@section('main__title')
    <h2>{{ trans('add_playlist') }}</h2>
    <a href="{{ route('backend.playlist.index') }}" class="main__title-link">{{ trans('playlist_list') }}</a>
@endsection

@section('content')
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <form action="{{ route('backend.playlist.store')  }}" method="POST" class="profile__form">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('title') }} (*)
                                        @error ('title')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <input type="text" name="title" class="profile__input" placeholder="{{ trans('title') }}" value="{{ old('title') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('order') }} (*)
                                        @error ('order')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <input type="text" name="order" class="profile__input" placeholder="{{ trans('ex') }}: 1" value="{{ old('order') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label" for="description">{{ trans('description') }} (*)
                                        @error ('description')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <textarea id="text" name="description" class="form__textarea" placeholder="{{ trans('description') }}">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="profile__btn" type="submit">{{ trans('save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('bower_components/bower_film/backend/js/sweetalert.min.js') }}"></script>
@endsection
