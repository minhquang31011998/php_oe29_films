@extends('backend.layouts.master')

@section('title')
    {{ trans('edit_user') }}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bower_film/backend/css/datatable.css') }}">
@endsection

@section('main__title')
    <h2>{{ trans('edit_user') }}</h2>
    <a href="{{ route('backend.user.index') }}" class="main__title-link">{{ trans('user_list') }}</a>
@endsection

@section('content')
    <div class="col-12">
        <div class="profile__content">
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <div class="profile__user">
                <div class="profile__meta profile__meta--green">
                    <h3>{{ $user['name'] }}</h3>
                    <span>{{ trans('id') }}: {{ $user['id'] }}</span>
                </div>
            </div>

            <ul class="nav nav-tabs profile__tabs" id="profile__tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">{{ trans('profile') }}</a>
                </li>
            </ul>

            <!-- profile mobile tabs nav -->
            <div class="profile__mobile-tabs" id="profile__mobile-tabs">
                <div class="profile__mobile-tabs-btn dropdown-toggle" role="navigation" id="mobile-tabs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <input type="button" value="Profile">
                    <span></span>
                </div>

                <div class="profile__mobile-tabs-menu dropdown-menu" aria-labelledby="mobile-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="1-tab" data-toggle="tab" href="#tab-1" role="tab" aria-controls="tab-1" aria-selected="true">{{ trans('profile') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- end profile mobile tabs nav -->

            <div class="profile__actions">
                <a href="#modal-status3" ></a>

                <button type="button" class="profile__action profile__action--banned" id="status">
                @if ($user['is_active'] == config('config.status_active'))
                    <i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>
                @else
                    <i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>
                @endif
                </button>

                <form action="{{ route('backend.user.destroy', $user['id']) }}" method="POST">
                @csrf
                @method('DELETE')
                    <button type="submit" id="delete_user" class="profile__action profile__action--delete">
                        <i class="icon ion-ios-trash" data-toggle="tooltip" title="Delete"></i>
                    </button>
                </form>
            </div>
            <input type="hidden" id="userId" value="{{ $user['id'] }}">
            <input type="hidden" id="active" value="{{ config('config.status_active') }}">
        </div>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
            <div class="col-12">
                <div class="row">
                    <!-- details form -->
                    <div class="col-12 col-lg-6">
                        <form action="{{ route('backend.user.update', $user['id'])  }}" method="POST" class="profile__form">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="profile__title">{{ trans('profile') }} {{ trans('detail') }}</h4>
                                </div>
                                @csrf
                                @method('PUT')

                                <div class="col-12 col-md-6 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('name') }}
                                        @error ('name')
                                            <span class="main__table-text--red">({{ $message }})</span>
                                        @enderror
                                        </label>
                                        <input id="username" type="text" name="name" class="profile__input" placeholder="{{ trans('name') }}" value="{{ $user['name'] }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('phone') }}
                                        @error ('phone')
                                            <span class="main__table-text--red">{{ $message }}</span>
                                        @enderror
                                        </label>
                                        <input type="text" name="phone" class="profile__input" placeholder="{{ trans('phone') }}" value="{{ $user['phone'] }}">
                                    </div>
                                </div>

                                <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                                    <div class="profile__group">
                                        <label class="profile__label">{{ trans('address') }}
                                        @error ('address')
                                            <span class="main__table-text--red">{{ $message }}</span>
                                        @enderror
                                        </label>
                                        <input type="text" name="address" class="profile__input" placeholder="{{ trans('address') }}" value="{{ $user['address'] }}">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="profile__btn" type="submit">{{ trans('save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- end details form -->

                    <!-- password form -->
                    <div class="col-12 col-lg-6">
                        <form action="{{ route('backend.user.changePassword', $user['id']) }}" class="profile__form" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="profile__title">{{ trans('change') }} {{ trans('password') }}</h4>
                                </div>

                                <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                    <div class="profile__group">
                                        <label class="profile__label" for="oldpass">{{ trans('old') }} {{ trans('password') }}</label>
                                        @error ('oldPassword')
                                            <div class="main__table-text--red">{{ $message }}</div>
                                        @enderror
                                        @if (session()->has('status'))
                                            <div class="main__table-text--red">{{ session('status') }}</div>
                                        @endif
                                        <input id="oldpass" type="password" name="oldPassword" class="profile__input">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                    <div class="profile__group">
                                        <label class="profile__label" for="newpass">{{ trans('new') }} {{ trans('password') }}</label>
                                        @error ('password')
                                            <div class="main__table-text--red">{{ $message }}</div>
                                        @enderror
                                        <input id="newpass" type="password" name="password" class="profile__input">
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-12 col-xl-6">
                                    <div class="profile__group">
                                        <label class="profile__label" for="confirmpass">{{ trans('password_confirm') }}</label>
                                        <input id="confirmpass" type="password" name="password_confirmation" class="profile__input">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="profile__btn" type="submit">{{ trans('change') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- end password form -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('bower_components/bower_film/backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('build/backend/js/cruduser.js') }}"></script>
@endsection
