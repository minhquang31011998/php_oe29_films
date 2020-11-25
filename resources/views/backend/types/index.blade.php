@extends('backend.layouts.master')

@section('title')
    {{ trans('type_list') }}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bower_film/backend/css/datatable.css') }}">
@endsection

@section('main__title')
    <h2>{{ trans('type_list') }}</h2>
    <div class="main__title-wrap">
        <div class="filter" id="filter__sort">
            <span class="filter__item-label">{{ trans('sort_by') }}:</span>

            <div class="filter__item-btn dropdown-toggle" role="navigation" id="filter-sort" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <input type="button" value="Date created">
                <span></span>
            </div>

            <ul class="filter__item-menu dropdown-menu scrollbar-dropdown" aria-labelledby="filter-sort">
                <li>{{ trans('date_created') }}</li>
                <li>{{ trans('title') }}</li>
            </ul>
        </div>

        <div class="main__title-form">
            <input type="text" placeholder="{{ trans('search') }}" id="filter">
            <button type="button">
                <i class="icon ion-ios-search"></i>
            </button>
        </div>
    </div>
    <a href="{{ route('backend.type.create') }}" class="main__title-link">{{ trans('add') }}</a>
@endsection

@section('content')
    <div class="col-12">
        <div class="main__table-wrap">
            <table class="main__table" id="types-table">
                <thead>
                    <tr>
                        <th>{{ trans('id') }}</th>
                        <th>{{ trans('title') }}</th>
                        <th>{{ trans('action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('bower_components/bower_film/backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('build/backend/js/type.js') }}"></script>
@endsection
