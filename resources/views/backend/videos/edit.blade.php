@extends('backend.layouts.master')

@section('title'){{ trans('edit_video') }}@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bower_film/backend/css/datatable.css') }}">
@endsection

@section('main__title')
    <h2>{{ trans('edit_video') }}</h2>
    <a href="{{ route('backend.video.index') }}" class="main__title-link">{{ trans('video_list') }}</a>
@endsection

@section('content')
    <div class="col-12">
        <div class="profile__content">
            <div class="profile__user">
                <div class="profile__meta profile__meta--green">
                    <h3>{{ $video['title'] }}</h3>
                    @if ($video['movie'] != null)
                        <span>({{ $video['movie']['name'] }})</span>
                    @endif
                </div>
            </div>

            <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link @if (session('status') != 'new') active show @endif" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">
                        {{ trans('detail') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if (session('status') == 'new') active show @endif" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                        {{ trans('source') }}
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
                            <a class="nav-link active" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">
                                {{ trans('detail') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="2-tab" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2" aria-selected="false">
                                {{ trans('source') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="profile__actions">
                <a href="#modal-status3" ></a>
                <button type="button" id="status"  class="profile__action profile__action--banned" value="{{ $video['id'] }}">
                @if ($video['status'] == config('config.status_active'))
                    <i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>
                @else
                    <i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>
                @endif
                </button>
                <form action="{{ route('backend.video.destroy' , $video['id']) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="delete_video" class="profile__action profile__action--delete">
                        <i class="icon ion-ios-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade @if (session('status') != 'new') active show @endif" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
            <div class="col-12">
                <form action="{{ route('backend.video.update', $video['id']) }}" class="form" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            <label class="profile__label">{{ trans('title') }}
                            @error ('title')
                                <span class="main__table-text--red">({{ $message }})</span>
                            @enderror
                            </label>
                            <input type="text" class="form__input" placeholder="{{ trans('title') }}" name="title" value="{{ $video['title'] }}">
                        </div>

                        <div class="col-12">
                            <label class="profile__label">{{ trans('description') }}
                            @error ('description')
                                <span class="main__table-text--red">({{ $message }})</span>
                            @enderror
                            </label>
                            <textarea id="text" name="description" class="form__textarea" placeholder="{{ trans('description') }}">{{ $video['description'] }}</textarea>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="profile__group">
                                <label class="profile__label">{{ trans('hashtag') }}</label>
                                @error ('tags')
                                    <div class="main__table-text--red">{{ $message }}</div>
                                @enderror
                                <input type="text" name="tags" class="profile__input" placeholder="{{ trans('ex') }}: #Tag1 #Tag2)" value="{{ $video['tags'] }}">
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <label class="profile__label">{{ trans('chap') }}
                            @error ('chap')
                                <span class="main__table-text--red">({{ $message }})</span>
                            @enderror
                            </label>
                            <input type="text" class="form__input" placeholder="{{ trans('ex') }}: 1" name="chap" value="{{ $video['chap'] }}">
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

        <input type="hidden" name="video_id" id="videoId" value="{{ $video['id'] }}">

        <div class="tab-pane fade @if (session('status') == 'new') active show @endif" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
            <div class="col-12">
                <a href="#modal-form" class="form__btn open-modal">
                    {{ trans('new_source') }}
                </a>
            </div>

            <a href="#modal-edit" class="open-modal" id="edit-source"></a>
            <div class="col-12">
                <div class="main__table-wrap">
                    <table class="main__table" id="sources-table">
                        <thead>
                            <tr>
                                <th>{{ trans('id') }}</th>
                                <th>{{ trans('source_key') }}</th>
                                <th>{{ trans('prioritize') }}</th>
                                <th>{{ trans('channel') }}</th>
                                <th>{{ trans('action') }}</th>
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
        <form action="{{ route('backend.source.store') }}" enctype="multipart/form-data" method="POST" id="frmCreateSource">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <input type="hidden" name="video_id" value="{{ $video['id'] }}">
            <input type="text" class="form__input" placeholder="{{ trans('prioritize') }}" name="movie_id" value="{{ $video['movie_id'] }}" hidden>

            <h6 class="modal__title">{{ trans('add') }} {{ trans('new_source') }}</h6>
            <div class="form">
                <div class="row">

                    <div class="col-12">
                        <label class="profile__label">{{ trans('prioritize') }} (*)</label>
                        <div class="main__table-text--red prioritize"></div>
                        <input type="text" class="form__input" name="prioritize">
                    </div>

                    <div class="col-12">
                        <label class="profile__label">{{ trans('channel') }}</label>
                        <select class="js-example-basic-single channels" name="channel_id">
                        @foreach ($channels as $channel)
                            <option  data-toggle="collapse" data-target=".multi-collapse" value="{{ $channel->id }}" @if ($channel->id == config('config.default_server'))selected="" @endif channel_type="{{ $channel->channel_type }}">{{ $channel->title }}</option>
                        @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="collapse show multi-collapse youtube">
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="profile__label">{{ trans('source_key') }} (*)</label>
                                            <div class="main__table-text--red source_key"></div>
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
                <button class="modal__btn modal__btn--apply" type="button" id="btn-create">{{ trans('save') }}</button>
                <button class="modal__btn modal__btn--dismiss" type="button">{{ trans('cancel') }}</button>
            </div>
        </form>
    </div>
    <div id="modal-edit" class="zoom-anim-dialog mfp-hide modal">
        <form method="POST" id="frmEditSource" enctype="multipart/form-data">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <input type="hidden" name="video_id" value="{{ $video['id'] }}">
            <input type="hidden" name="source_id" value="">
            <h6 class="modal__title">{{ trans('edit_source') }}</h6>
            <div class="form">
                <div class="row">
                    <div class="col-12">
                        <label class="profile__label">{{ trans('prioritize') }} (*)</label>
                        <div class="main__table-text--red prioritize"></div>
                        <input type="text" class="form__input" name="prioritize">
                    </div>

                    <div class="col-12">
                        <label class="profile__label">{{ trans('channel') }}</label>
                        <select class="channels" name="channel_id">
                        @foreach ($channels as $channel)
                            <option  data-toggle="collapse" data-target=".multi-collapse" value="{{ $channel->id }}" channel_type="{{ $channel->channel_type }}">{{ $channel->title }}</option>
                        @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="collapse show multi-collapse youtube">
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="profile__label">{{ trans('source_key') }} (*)</label>
                                            <div class="main__table-text--red source_key"></div>
                                            <input type="text" class="form__input" placeholder="{{ trans('ex') }}: f41QDkSqEQgs" name="source_key">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="source_id">
                </div>
            </div>

            <div class="modal__btns">
                <button class="modal__btn modal__btn--apply" type="button" id="btn-edit">{{ trans('save') }}</button>
                <button class="modal__btn modal__btn--dismiss" type="button">{{ trans('cancel') }}</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('bower_components/bower_film/backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('build/backend/js/source.js') }}"></script>
    <script src="{{ asset('build/backend/js/crudvideo.js') }}"></script>
@endsection
