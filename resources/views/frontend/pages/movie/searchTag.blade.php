@extends('frontend.layouts.master')

@section('title'){{ trans('search') }}@endsection

@section('header_content')
    <section class="section section--first section--bg" data-bg="">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section__wrap">
                        <!-- section title -->
                        <h2 class="section__title">{{ trans('search') }}: {{ $tag['name'] }}</h2>
                        <!-- end section title -->

                        <!-- breadcrumb -->
                        <ul class="breadcrumb">
                            <li class="breadcrumb__item">
                                <a href="{{ route('frontend.home') }}">{{ trans('home') }}</a>
                            </li>
                            <li class="breadcrumb__item breadcrumb__item--active">{{ $tag['name'] }}</li>
                        </ul>
                        <!-- end breadcrumb -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('filter')
    <div class="filter">
        <div class="container">
            <div class="row">
                <div class="col-12">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="catalog">
        <div class="container">
            <div class="row">
            @foreach ($movies as $movie)
                <!-- card -->
                <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                    <div class="card">
                        <div class="card__cover">
                            <img src="{{ asset($movie['card_cover']) }}" alt="">
                            <a href="{{ route('frontend.watchMovie', [$movie['slug'], config('config.default_server'), config('config.default_prioritize')])}}" class="card__play">
                                <i class="icon ion-ios-play"></i>
                            </a>
                        </div>
                        <div class="card__content">
                            <h3 class="card__title"><a href="{{ route('frontend.watchMovie', [$movie['slug'], config('config.default_server'), config('config.default_prioritize')]) }}">{{ $movie['name'] }}</a></h3>
                            <span class="card__category">
                            @foreach ($movie['types'] as $type)
                                <a href="{{ route('frontend.catalog',['type', $type['slug']]) }}">{{ trans($type['title']) }}</a>
                            @endforeach
                            </span>
                            <span class="card__rate"><i class="icon ion-ios-star"></i>{{ $movie['rate'] }}</span>
                        </div>
                    </div>
                </div>
                <!-- end card -->
            @endforeach
                <!-- paginator -->
                <div class="col-12">
                    {!! $movies->render('vendor.pagination.custom_view') !!}
                </div>
                <!-- end paginator -->
            </div>
        </div>
    </div>
@endsection

