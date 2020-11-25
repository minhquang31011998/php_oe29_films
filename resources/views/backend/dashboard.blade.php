
@extends('backend.layouts.master')

@section('title')
    {{ trans('dashboard') }}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bower_film/backend/css/datatable.css') }}">
@endsection

@section('main__title')
    <div class="col-12">
        <div class="main__title">
            <h2>{{ trans('dashboard') }}</h2>

        </div>
    </div>
    <!-- end main title -->

    <!-- stats -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats">
            <span>{{ trans('total') }} {{ trans('film') }}</span>
            <p>{{ $totalMovie }}</p>
            <i class="icon ion-ios-film"></i>
        </div>
    </div>
    <!-- end stats -->

    <!-- stats -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats">
            <span>{{ trans('total') }} {{ trans('playlist') }}</span>
            <p>{{ $totalPlaylist }}</p>
            <i class="icon ion-ios-stats"></i>
        </div>
    </div>
    <!-- end stats -->

    <!-- stats -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats">
            <span>{{ trans('total') }} {{ trans('tag') }}</span>
            <p>{{ $totalTag }}</p>
            <i class="icon fa fa-tag"></i>
        </div>
    </div>
    <!-- end stats -->

    <!-- stats -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stats">
            <span>{{ trans('total') }} {{ trans('type') }}</span>
            <p>{{ $totalType }}</p>
            <i class="icon fa fa-bars"></i>
        </div>
    </div>
    <!-- end stats -->

    <!-- dashbox -->
    <div class="col-12 col-xl-6">
        <div class="dashbox">
            <div class="dashbox__title">
                <h3><i class="icon ion-ios-trophy"></i>{{ trans('top_film') }}</h3>

                <div class="dashbox__wrap">
                    <a href="{{ route('backend.movie.index') }}" class="dashbox__more">{{ trans('view_all') }}</a>
                </div>
            </div>

            <div class="dashbox__table-wrap">
                <table class="main__table main__table--dash">
                    <thead>
                        <tr>
                            <th>{{ trans('index') }}</th>
                            <th>{{ trans('name') }}</th>
                            <th>{{ trans('rate') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topMovies as $index => $topMovie)
                        <tr>
                            <td>
                                <div class="main__table-text">{{ $index + 1 }}</div>
                            </td>
                            <td>
                                <div class="main__table-text">{{ $topMovie['name'] }}</div>
                            </td>
                            <td>
                                <div class="main__table-text main__table-text--rate"><i class="icon ion-ios-star"></i>{{ $topMovie['rate'] }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- end dashbox -->

    <!-- dashbox -->
    <div class="col-12 col-xl-6">
        <div class="dashbox">
            <div class="dashbox__title">
                <h3><i class="icon ion-ios-contacts"></i>{{ trans('last_user') }}</h3>

                <div class="dashbox__wrap">
                    <a class="dashbox__more" href="{{ route('backend.user.index') }}">{{ trans('view_all') }}</a>
                </div>
            </div>

            <div class="dashbox__table-wrap">
                <table class="main__table main__table--dash">
                    <thead>
                        <tr>
                            <th>{{ trans('index') }}</th>
                            <th>{{ trans('name') }}</th>
                            <th>{{ trans('email') }}</th>
                            <th>{{ trans('status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td>
                                <div class="main__table-text">{{ $index + 1 }}</div>
                            </td>
                            <td>
                                <div class="main__table-text">{{ $user['name'] }}</div>
                            </td>
                            <td>
                                <div class="main__table-text main__table-text--grey">{{ $user['email'] }}</div>
                            </td>
                            <td>
                                @if($user['is_active'])
                                <div class="main__table-text main__table-text--green">{{ trans('active') }}</div>
                                @else
                                <div class="main__table-text main__table-text--red">{{ trans('hidden') }}</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- end dashbox -->
@endsection

@section('js')
    <script src="{{ asset('bower_components/bower_film/backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/sweetalert.min.js') }}"></script>
@endsection
