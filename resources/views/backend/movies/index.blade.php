@extends('backend.layouts.master')

@section('title'){{ trans('list_film') }}@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bower_film/backend/css/datatable.css') }}">
@endsection

@section('main__title')
    <h2>{{ trans('list_film') }}</h2>
    <div class="main__title-wrap">
        <!-- filter sort -->
        <div class="filter" id="filter__sort">
            <span class="filter__item-label">{{ trans('sort_by') }}:</span>

            <div class="filter__item-btn dropdown-toggle" role="navigation" id="filter-sort" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <input type="button" value="{{ trans('date_created') }}" id="sort">
                <span></span>
            </div>

            <ul class="filter__item-menu dropdown-menu scrollbar-dropdown" aria-labelledby="filter-sort">
                <li>{{ trans('date_created') }}</li>
                <li>{{ trans('name') }}</li>
                <li>{{ trans('release_year') }}</li>
                <li>{{ trans('rate') }}</li>
                <li>{{ trans('nomination') }}</li>
            </ul>
        </div>
        <!-- end filter sort -->

        <!-- search -->
        <div class="main__title-form">
            <input type="text" placeholder="{{ trans('search') }}.." id="filter">
            <button type="button">
                <i class="icon ion-ios-search"></i>
            </button>
        </div>
        <!-- end search -->
    </div>
    <a href="{{ route('backend.movie.create') }}" class="main__title-link">{{ trans('add') }}</a>
@endsection

@section('content')
    <div class="col-12">
        <div class="main__table-wrap">
            <table class="main__table" id="movies-table">
                <thead>
                <tr>
                    <th>{{ trans('id') }}</th>
                    <th>{{ trans('name') }}</th>
                    <th>{{ trans('slug') }}</th>
                    <th>{{ trans('action') }}</th>
                </tr>
                </thead>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('bower_components/bower_film/backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('build/backend/js/movie.js') }}"></script>
@endsection
