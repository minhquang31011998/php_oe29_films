@extends('backend.layouts.master')

@section('title')
    {{ trans('add_movie') }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/jquery.tagsinput-revisited.css')}}">
    <link rel="stylesheet" href="{{ asset('build/backend/css/film.css') }}">
@endsection

@section('main__title')
    <h2>{{ trans('add_movie') }}</h2>
    <a href="{{ route('backend.movie.index') }}" class="main__title-link">{{ trans('list_film') }}</a>
@endsection

@section('content')
    <div class="col-12">
        <form action="{{ route('backend.movie.store') }}" class="form" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-md-5 form__cover">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-12">
                            <div class="form__img">
                                <label for="form__img-upload">{{ trans('upload_cover') }} (270 x 400)</label>
                                <input id="form__img-upload" name="card_cover" type="file" accept=".png, .jpg, .jpeg">
                                <img id="form__img" src="#" alt=" ">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-7 form__content">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <label class="profile__label">{{ trans('name') }} (*)
                            @error ('name')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            </label>
                            <input type="text" class="form__input" placeholder="{{ trans('name') }}(*)" name="name" value="{{ old('name') }}">
                        </div>

                        <div class="col-12 col-lg-6">
                            <label class="profile__label">{{ trans('name_origin') }} (*)
                            @error ('name_origin')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            </label>
                            <input type="text" class="form__input" placeholder="{{ trans('name_origin') }}(*)" name="name_origin" value="{{ old('name_origin') }}">
                        </div>

                        <div class="col-12">
                            <label class="profile__label">{{ trans('description') }} (*)
                            @error ('description')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            </label>
                            <textarea id="text" class="form__textarea" placeholder="{{ trans('Description') }}(*)" name="description">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="profile__label">{{ trans('release_year') }} (*)
                            @error ('release_year')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            </label>
                            <input type="text" class="form__input" placeholder="{{ trans('release_year') }}(*)" name="release_year" value="{{ old('release_year') }}">
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="profile__label">{{ trans('running_time') }} (*)
                            @error ('runtime')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            </label>
                            <input type="text" class="form__input" placeholder="{{ trans('running_time') }} ({{ trans('minute') }})(*)" name="runtime" value="{{ old('runtime') }}">
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="profile__label">{{ trans('quality') }} (*)
                            <select class="js-example-basic-single" id="quality" name="quality">
                            @foreach ($qualities as $quality)
                                <option value="{{ $quality['order'] }}" @if ( $quality['order'] == old('quality') ) selected @endif>{{ $quality['name'] }}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="profile__label">{{ trans('age') }} (*)
                            @error ('age')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            </label>
                            <input type="text" class="form__input" placeholder="{{ trans('age') }}(*)" name="age" value="{{ old('age') }}">
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="profile__label">{{ trans('country') }} (*)
                            @error ('country')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            </label>
                            <select class="js-example-basic-multiple" id="country" name="country">
                            @foreach ($countries as $country)
                                <option value="{{ $country['order'] }}" @if ( $country['order'] == old('country') ) selected @endif>{{ trans($country['name']) }}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="profile__label">{{ trans('genre') }} (*)
                            @error ('genre')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            </label>
                            <select class="js-example-basic-multiple" id="genre" name="genre">
                            @foreach ($genres as $genre)
                                <option value="{{ $genre['order'] }}" @if ( $genre['order'] == old('genre') ) selected @endif>{{ trans($genre['name']) }}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label class="profile__label">{{ trans('type') }} (*)
                            @error ('type[]')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            </label>
                            <select class="js-example-basic-multiple" id="types" multiple="multiple" name="types[]">
                            @foreach ($types as $type)
                                <option value="{{ $type['id'] }}">{{ trans($type['title']) }}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="profile__label">{{ trans('tag') }}
                            @error ('tag')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            </label>
                            <input id="tags" name="tags" type="text" value="{{ old('tags') }}" class="form__input">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="form__btn">{{ trans('publish') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.tagsinput-revisited.js')}}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('build/backend/js/crudmovie.js') }}"></script>
@endsection
