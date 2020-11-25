@extends('backend.layouts.master')

@section('title'){{ trans('add_video') }}@endsection

@section('main__title')
    <h2>{{ trans('add_video') }}</h2>
    <a href="{{ route('backend.video.index') }}" class="main__title-link">{{ trans('video_list') }}</a>
@endsection

@section('content')
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <form action="{{ route('backend.video.store') }}" method="POST" class="profile__form" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('title') }} (*)
                                        @error('title')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <input type="text" name="title" class="profile__input" placeholder="{{ trans('title') }}" value="{{ old('title') }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('description') }} (*)
                                        @error('description')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <textarea id="text" name="description" class="form__textarea" placeholder="{{ trans('description') }}">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('hashtag') }}</label>
                                        <input type="text" name="tags" class="profile__input" placeholder="{{ trans('ex') }}: #Tag1 #Tag2)">
                                    </div>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label class="profile__label">{{ trans('channel') }} (*)</label>
                                    <select class="js-example-basic-single" id="channels" name="channel_id">
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel['id'] }}" @if ($channel['id'] == config('config.default_channel')) selected="" @endif channel_type="{{ $channel['channel_type'] }}">{{ $channel['title'] }}</option>
                                    @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="collapse show multi-collapse" id="youtube">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="profile__label">{{ trans('source_key') }} (*)
                                                        @error('source_key')
                                                            <span class="main__table-text--red">({{ $message }})</span>
                                                        @enderror
                                                        </label>
                                                        <input type="text" class="form__input" placeholder="{{ trans('ex') }}: f41QDkSqEQg" name="source_key">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
    <script src="{{ asset('build/backend/js/crudvideo.js') }}"></script>
@endsection
