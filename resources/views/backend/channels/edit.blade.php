@extends('backend.layouts.master')

@section('title')
    {{ trans('edit_channel') }}
@endsection

@section('main__title')
    <h2>{{ trans('edit_channel') }}</h2>
    <a href="{{ route('backend.channel.index') }}" class="main__title-link">{{ trans('channel_list') }}</a>
@endsection

@section('content')
    <div class="col-12">
        <div class="profile__content">
            <div class="profile__user">
                <div class="profile__meta profile__meta--green">
                    <h3>{{ $channel['title'] }}</h3>
                </div>
            </div>
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <input type="hidden" id="channelId" value="{{ $channel['id'] }}">

            <div class="profile__actions">
                <a href="#modal-status3" ></a>
                <button type="button" class="profile__action profile__action--banned" id="status">
                @if ($channel['status'] == config('config.status_active'))
                    <i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>
                @else
                    <i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>
                @endif
                </button>

                <form action="{{ route('backend.channel.destroy', $channel['id']) }}" method="POST">
                @csrf
                @method('DELETE')
                    <button type="submit" id="delete_channel" class="profile__action profile__action--delete">
                        <i class="icon ion-ios-trash" data-toggle="tooltip" title="Delete"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <form action="{{ route('backend.channel.update', $channel['id'])  }}" method="POST" class="profile__form">
                            <div class="row">
                                @csrf
                                @method('PUT')
                                <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('title') }} (*)
                                        @error ('title')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <input id="username" type="text" name="title" class="profile__input" placeholder="{{ $channel['title'] }}" value="{{ $channel['title'] }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                    <div class="profile__group">
                                        <label class="profile__label" for="email">{{ trans('link') }}
                                        @error ('link')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <input id="link" type="text" name="link" class="profile__input" placeholder="{{ $channel['link'] }}" value="{{ $channel['link'] }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                    <div class="profile__group">
                                        <label class="profile__label" for="email">{{ trans('channel_type') }} (*)</label>
                                        <select name="channel_type" id="channel_type">
                                        @foreach ($channelTypes as $channelType)
                                            <option value="{{ $channelType['order'] }}" @if ($channel['channel_type'] == $channelType['order']) selected @endif>{{ $channelType['name'] }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label" for="oldpass">{{ trans('description') }} (*)
                                        @error('description')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <textarea id="text" name="description" class="form__textarea" placeholder="Description">{{ $channel['description'] }}</textarea>
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
    <script src="{{ asset('build/backend/js/crudchannel.js') }}"></script>
@endsection
