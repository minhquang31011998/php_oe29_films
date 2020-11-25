@extends('backend.layouts.master')

@section('title')
    {{ trans('edit_movie') }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/jquery.tagsinput-revisited.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('build/backend/css/film.css') }}">
@endsection

@section('main__title')
    <h2>{{ trans('edit_movie') }}</h2>
    <a href="{{ route('backend.movie.index') }}" class="main__title-link">{{ trans('list_film') }}</a>
@endsection

@section('content')
    <div class="col-12">
        <div class="profile__content">
            <div class="profile__user">
                <div class="profile__meta profile__meta--green">
                    <h3>{{ $movie['name'] }}</h3>
                    <span>{{ trans('genre') }}:
                    @foreach ($genres as $genre)
                        @if ($movie['genre'] == $genre['order'] )
                            {{ $genre['name'] }}
                        @endif
                    @endforeach
                    </span>
                </div>
            </div>

            <input type="hidden" id="movieId" value="{{ $movie['id'] }}">

            <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link @if (\session('status') != 'new') active show @endif" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">{{ trans('detail') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if (session('status' ) == 'new') active show @endif" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                    @if($movie['genre'] == config('config.genre_of_movie'))
                        {{ trans('video') }}
                    @else
                        {{ trans('playlist') }}
                    @endif
                    </a>
                </li>
            </ul>

            <div class="profile__mobile-tabs" id="profile__mobile-tabs">
                <div class="profile__mobile-tabs-btn dropdown-toggle" role="navigation" id="mobile-tabs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <input type="button" value="Profile">
                    <span></span>
                </div>

                <div class="profile__mobile-tabs-menu dropdown-menu" aria-labelledby="mobile-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link @if (session('status') != 'new') active show @endif" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">{{ trans('detail') }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link @if(session('status') == 'new') active show @endif" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                            @if ($movie['genre'] == config('config.genre_of_movie'))
                                {{ trans('video') }}
                            @else
                                {{ trans('playlist') }}
                            @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="profile__actions">
                <a href="#modal-status3" ></a>
                <form action="{{ route('backend.movie.destroy' , $movie['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="delete_movie" class="profile__action profile__action--delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade @if (session('status') != 'new') active show @endif" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
            <div class="col-12">
                <form action="{{ route('backend.movie.update', $movie['id']) }}" class="form" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12 col-md-5 form__cover">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-12">
                                    <div class="form__img">
                                        <label for="form__img-upload">{{ trans('upload_cover') }} (270 x 400)</label>
                                        <input id="form__img-upload" name="card_cover" type="file" accept=".png, .jpg, .jpeg">
                                        <img id="form__img" src="{{ asset($movie['card_cover']) }}" alt=" ">
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
                                    <input type="text" class="form__input" placeholder="{{ trans('name') }}" name="name" value="{{ $movie['name'] }}">
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="profile__label">{{ trans('name_origin') }} (*)
                                    @error ('name_origin')
                                        <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    </label>
                                    <input type="text" class="form__input" placeholder="{{ trans('name_origin') }}" name="name_origin" value="{{ $movie['name_origin'] }}">
                                </div>

                                <div class="col-12">
                                    <label class="profile__label">{{ trans('description') }} (*)
                                    @error ('description')
                                        <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    </label>
                                    <textarea id="text" class="form__textarea" placeholder="{{ trans('Description') }}" name="description">{!! $movie['description'] !!}</textarea>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="profile__label">{{ trans('release_year') }} (*)
                                    @error ('release_year')
                                        <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    </label>
                                    <input type="text" class="form__input" placeholder="{{ trans('release_year') }}" name="release_year" value="{{ $movie['release_year'] }}">
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="profile__label">{{ trans('running_time') }} (*)
                                    @error ('runtime')
                                        <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    </label>
                                    <input type="text" class="form__input" placeholder="{{ trans('running_time') }} ({{ trans('minute') }})" name="runtime" value="{{ $movie['runtime'] }}">
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="profile__label">{{ trans('quality') }} (*)
                                    </label>
                                    <select class="js-example-basic-single" id="quality" name="quality">
                                    @foreach ($qualities as $quality)
                                        <option value="{{ $quality['order'] }}" @if ($movie['quality'] == $quality['order'] ) selected @endif>{{ $quality['name'] }}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-3">
                                    <label class="profile__label">{{ trans('age') }} (*)
                                    @error ('age')
                                        <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    </label>
                                    <input type="text" class="form__input" placeholder="{{ trans('age') }}" name="age" value="{{ $movie['age'] }}">
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="profile__label">{{ trans('country') }} (*)
                                    @error ('country')
                                        <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    </label>
                                    <select class="js-example-basic-multiple" id="country" name="country">
                                    @foreach ($countries as $country)
                                        <option value="{{ $country['order'] }}" @if ($movie['country'] == $country['order']) selected @endif>{{ $country['name'] }}</option>
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
                                        <option value="{{ $type['id'] }}"
                                            @foreach ($movieTypes as $movieType)
                                                @if ($movieType['id'] == $type['id']) selected @endif
                                            @endforeach>
                                            {{ trans($type['title']) }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="profile__label">{{ trans('tag') }}
                                    @error ('tag')
                                        <div class="main__table-text--red">{{ $message }}</div>
                                    @enderror
                                    </label>
                                    <input id="tags" name="tags" type="text" value="{{ $movieTags }}" class="form__input">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="form__btn">{{ trans('save') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="tab-pane fade @if (session('status') == 'new') active show @endif" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
        @if ($movie['genre'] == config('config.genre_of_movie'))
            @if (count($movieVideos) == 0)
                <div class="col-12">
                    <a href="#modal-form" class="form__btn open-modal">
                        {{ trans('add_video') }}
                    </a>
                </div>
                <div class="col-12 or-select">{{ trans('or_select') }}</div>

                <div class="col-12">
                    <div class="main__table-wrap">
                        <table class="main__table" id="videos-table">
                            <thead>
                                <tr>
                                    <th>{{ trans('index') }}</th>
                                    <th>{{ trans('title') }}</th>
                                    <th>{{ trans('action') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            @else
                @foreach ($movieVideos as $video)
                    <div class="col-12">
                        <a href="{{ route('backend.video.edit', [$video->id, $movie['id']]) }}" class="form__btn">
                            {{ trans('edit_video') }}
                        </a>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="main__table">
                                            <tbody>
                                                <tr>
                                                    <td class="main__table-text">{{ trans('id') }}</td>
                                                    <td>
                                                        <div class="main__table-text">{{ $video->id }}</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="main__table-text">{{ trans('title') }}</td>
                                                    <td>
                                                        <div class="main__table-text">{{ $video->title }}</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="main__table-text">{{ trans('description') }}</td>
                                                    <td>
                                                        <div class="main__table-text">{{ $video->description }}</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="main__table-text">{{ trans('slug') }}</td>
                                                    <td>
                                                        <div class="main__table-text">{{ $video->slug }}</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <form action="{{ route('backend.video.detach', $video['id']) }}" method="POST">
                            @csrf
                            <button type="submit" class="form__btn">{{ trans('detach_video') }}</button>
                        </form>
                    </div>
                @endforeach
            @endif
        @else
            <div class="col-12">
                <div class="main__table-wrap">
                    <table class="main__table" id="playlists-table">
                        <thead>
                            <tr>
                                <th>{{ trans('index') }}</th>
                                <th>{{ trans('title') }}</th>
                                <th>{{ trans('order') }}</th>
                                <th>{{ trans('action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div class="col-12">
                <a href="#modal-form-1" class="form__btn open-modal">
                    {{ trans('add_playlist') }}
                </a>
                <a href="#modal-edit-playlist" id="edit-playlist" class="open-modal"></a>
            </div>

            <div class="col-12 or-select">{{ trans('or_select') }}</div>

            <div class="col-12">
                <div class="main__table-wrap">
                    <table class="main__table" id="no-movie-playlists-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ trans('index') }}</th>
                                <th>{{ trans('title') }}</th>
                                <th>{{ trans('order') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        @endif
        </div>
    </div>
@endsection

@section('modal')
    <div id="modal-form" class="zoom-anim-dialog mfp-hide modal">
        <form action="{{ route('backend.movie.updateVideo', $movie['id']) }}" enctype="multipart/form-data" method="POST" id="frmCreateVideo">
            <meta name="csrf-token" content="{{ csrf_token() }}"/>
            <h6 class="modal__title">{{ trans('add_video') }}</h6>
            <div class="form">
                <div class="row">
                    <div class="col-12">
                        <div class="main__table-text--red description"></div>
                        <input type="text" class="form__input" placeholder="{{ trans('title') }} (*)" name="title" value="{{ old('title') }}">
                    </div>

                    <div class="col-12">
                        <div class="main__table-text--red tags"></div>
                        <input type="text" class="form__input" placeholder="{{ trans('hashtag') }}" name="tags" value="{{ old('tags') }}">
                    </div>

                    <div class="col-12">
                        <div class="main__table-text--red description"></div>
                        <textarea class="form__textarea" placeholder="{{ trans('description') }} (*)" name="description">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="profile__label">{{ trans('channel') }}</label>
                        <select class="js-example-basic-single channels" id="channels" name="channel_id">
                        @foreach ($channels as $channel)
                            <option value="{{ $channel['id'] }}" @if($channel['id'] == old('channel_id')) selected @endif channel_type="{{ $channel['channel_type'] }}">{{ $channel['title'] }}</option>
                        @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">

                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="collapse show multi-collapse" id="youtube">
                                    <div class="row">
                                        <div class="col-12">
                                            <label id="video1" class="profile__label">{{ trans('source_key') }}</label>
                                            <div class="main__table-text--red video"></div>
                                            <input type="text" class="form__input" placeholder="{{ trans('ex') }}: f41QDkSqEQg" name="source_key">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal__btns">
                <button class="modal__btn modal__btn--apply" type="button" id="btn-create-video">{{ trans('save') }}</button>
                <button class="modal__btn modal__btn--dismiss" type="button">{{ trans('cancel') }}</button>
            </div>
        </form>
    </div>

    <div id="modal-form-1" class="zoom-anim-dialog mfp-hide modal">
        <form enctype="multipart/form-data" method="POST" id="frmCreatePlaylist">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
            <h6 class="modal__title">{{ trans('add_playlist') }}</h6>
            <div class="form">
                <div class="row">
                    <div class="col-12">
                        <label class="profile__label">{{ trans('title') }} (*)
                            <div class="main__table-text--red title"></div>
                        </label>
                        <input type="text" class="form__input" placeholder="{{ trans('title') }} (*)" name="title" value="">
                    </div>
                    <div class="col-12">
                        <label class="profile__label">{{ trans('description') }} (*)
                            <div class="main__table-text--red title"></div>
                        </label>
                        <textarea class="form__textarea" placeholder="{{ trans('description') }} (*)" name="description"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="profile__label">{{ trans('order') }} (*)
                            <div class="main__table-text--red title"></div>
                        </label>
                        <input type="text" class="form__input" placeholder="{{ trans('order') }} (*)" name="order" value="">
                    </div>
                </div>
            </div>

            <div class="modal__btns">
                <button class="modal__btn modal__btn--apply" type="button" id="btn-create-playlist">{{ trans('save') }}</button>
                <button class="modal__btn modal__btn--dismiss" type="button">{{ trans('cancel') }}</button>
            </div>
        </form>
    </div>

    <div id="modal-edit-playlist" class="zoom-anim-dialog mfp-hide modal">
        <form method="POST" id="frmEditPlaylist">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <input type="hidden" name="movie_id" value="{{ $movie['id'] }}">
            <input type="hidden" name="playlist_id" value="">
            <h6 class="modal__title">{{ trans('edit_playlist') }}</h6>
            <div class="form">
                <div class="row">
                    <div class="col-12">
                        <label class="profile__label">{{ trans('title') }} (*)
                            <div class="main__table-text--red title"></div>
                        </label>
                        <input type="text" class="form__input" placeholder="{{ trans('title') }}" name="title" value="">
                    </div>
                    <div class="col-12">
                        <label class="profile__label">{{ trans('description') }} (*)
                            <div class="main__table-text--red title"></div>
                        </label>
                        <textarea class="form__textarea" placeholder="{{ trans('description') }}" name="description"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="profile__label">{{ trans('order') }} (*)
                            <div class="main__table-text--red title"></div>
                        </label>
                        <input type="text" class="form__input" placeholder="{{ trans('order') }}" name="order" value="">
                    </div>
                </div>
            </div>

            <div class="modal__btns">
                <button class="modal__btn modal__btn--apply" type="button" id="btn-update-playlist">{{ trans('save') }}</button>
                <button class="modal__btn modal__btn--dismiss" type="button">{{ trans('cancel') }}</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('bower_components/bower_film/backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.tagsinput-revisited.js') }}"></script>
    <script src="{{ asset('build/backend/js/crudmovie.js') }}"></script>
@endsection
