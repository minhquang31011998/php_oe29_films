@extends('backend.layouts.master')

@section('title'){{ trans('edit_playlist') }}@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bower_film/backend/css/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('build/backend/css/playlist.css') }}">
@endsection

@section('main__title')
    <h2>{{ trans('edit_playlist') }}</h2>
    <a href="{{ route('backend.playlist.index') }}" class="main__title-link">{{ trans('playlist_list') }}</a>
@endsection

@section('content')
    <div class="col-12">
        <div class="profile__content">
            <div class="profile__user">
                <div class="profile__meta profile__meta--green">
                    <h3>{{ $playlist['title'] }}</h3>
                </div>
            </div>
            <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link @if (session('status') != 'new') active show @endif" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">
                        {{ trans('detail') }}
                    </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link @if (session('status') == 'new') active show @endif" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                        {{ trans('video') }}
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
                            <a class="nav-link @if (session('status') != 'new') active show @endif" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">{{ trans('detail') }}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link @if (session('status') == 'new') active show @endif" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">{{ trans('video') }}</a>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="profile__actions">
                <a href="#modal-status3" ></a>
                <button type="button" class="profile__action profile__action--banned" id="status">
                @if ($playlist['status'] == config('config.status_active'))
                    <i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>
                @else
                    <i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>
                @endif
                </button>
                <form action="{{ route('backend.playlist.destroy' , $playlist['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="delete_playlist" class="profile__action profile__action--delete">
                        <i class="icon ion-ios-trash" data-toggle="tooltip" title="Delete"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade @if (\session('status') != 'new') active show @endif" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
            <div class="col-12">
                <form action="{{ route('backend.playlist.update', $playlist['id']) }}" class="form" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')
                    @if (Session::has('movieId'))
                    <input type="hidden" name="movieId" value="{{ Session::get('movieId') }}">
                    @endif
                    <div class="row">
                        <div class="col-12">
                            @error ('title')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            <label class="profile__labele" for="title">{{ trans('title') }}</label>
                            <input type="text" class="form__input" placeholder="{{ trans('title') }}" name="title" value="{{ $playlist['title'] }}">
                        </div>

                        <div class="col-12">
                            @error ('description')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            <label class="profile__labele" for="description">{{ trans('description') }}</label>
                            <textarea id="text" class="form__textarea" placeholder="{{ trans('description') }}" name="description">{!! $playlist['description'] !!}</textarea>
                        </div>

                        <div class="col-12">
                            @error ('order')
                                <div class="main__table-text--red">{{ $message }}</div>
                            @enderror
                            <label class="profile__labele" for="order">{{ trans('order') }}</label>
                            <input type="text" class="form__input" placeholder="{{ trans('order') }}" name="order" value="{{ $playlist['order'] }}">
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="form__btn">{{ trans('save') }}</button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="playlistId" id="playlistId" value="{{ $playlist['id'] }}">
                    </div>
                </form>
            </div>
        </div>

        <div class="tab-pane fade @if (session('status') == 'new') active show @endif" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
            <div class="col-12">
                <div class="main__table-wrap">
                    <table class="main__table" id="videos-table">
                        <thead>
                            <tr>
                                <th>{{ trans('index') }}</th>
                                <th>{{ trans('title') }}</th>
                                <th>{{ trans('chap') }}</th>
                                <th>{{ trans('action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="col-12">
                <a href="#modal-form" class="form__btn open-modal">
                    {{ trans('add_video') }}
                </a>
            </div>
            <div class="col-12" style="text-align: center;color: white">{{ trans('or_select') }}</div>
            <div class="col-12">
                <div class="main__table-wrap">
                    <table class="main__table" id="no-playlist-videos-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ trans('index') }}</th>
                                <th>{{ trans('title') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div id="modal-form" class="zoom-anim-dialog mfp-hide modal">
        <form action="{{ route('backend.video.store') }}" enctype="multipart/form-data" method="POST" id="frmCreateVideo">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <h6 class="modal__title">{{ trans('add_video') }}</h6>
            <div class="form">
                <div class="row">
                    <div class="col-12">
                        <label class="profile__label">{{ trans('title') }} (*)
                            <div class="main__table-text--red title"></div>
                        </label>
                        <input type="text" class="form__input" placeholder="{{ trans('title') }}" name="title" value="{{ old('title') }}">
                    </div>

                    <div class="col-12">
                        <label class="profile__label">{{ trans('hashtag') }}
                        </label>
                        <input type="text" class="form__input" placeholder="{{ trans('hashtag') }}" name="tags" value="{{ old('tags') }}">
                    </div>

                    <div class="col-12">
                        <label class="profile__label">{{ trans('chap') }} (*)
                            <div class="main__table-text--red title"></div>
                        </label>
                        <input type="text" class="form__input" placeholder="{{ trans('chap') }}" name="chap" value="{{ old('chap') }}">
                    </div>

                    <div class="col-12">
                        <label class="profile__label">{{ trans('description') }} (*)
                            <div class="main__table-text--red title"></div>
                        </label>
                        <textarea  class="form__textarea" placeholder="{{ trans('description') }}" name="description">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="profile__label">{{ trans('channel') }}</label>
                        <select class="js-example-basic-single channels" id="channels" name="channel_id">
                        @foreach ($channels as $channel)
                            <option value="{{ $channel['id'] }}" @if ($channel['id'] == old('channel_id')) selected @endif channel_type="{{ $channel['channel_type'] }}">{{ $channel['title'] }}</option>
                        @endforeach
                        </select>
                    </div>

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
                    <input type="hidden" name="playlist_id" value="{{ $playlist['id'] }}">
                </div>
            </div>

            <div class="modal__btns">
                <button class="modal__btn modal__btn--apply" type="button" id="btn-create-video">{{ trans('save') }}</button>
                <button class="modal__btn modal__btn--dismiss" type="button">{{ trans('cancel') }}</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('bower_components/bower_film/backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('build/backend/js/crudplaylist.js') }}"></script>
@endsection
