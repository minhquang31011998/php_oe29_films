@extends('backend.layouts.master')

@section('title')
    {{ trans('chart') }}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('bower_components/bower_film/backend/css/datatable.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('build/backend/css/chart.css') }}">
@endsection

@section('main__title')
    <div class="col-12">
        <div class="main__title">
            <h2>{{ trans('chart') }}</h2>

        </div>
    </div>

    <div id="container" data-order="{{ $types }}"></div>
@endsection

@section('js')
    <script src="{{ asset('bower_components/bower_film/backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/js/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('build/backend/js/chart.js') }}"></script>
@endsection
