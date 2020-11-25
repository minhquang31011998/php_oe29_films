@extends('frontend.layouts.master')

@section('title'){{ trans('web_title') }}@endsection

@section('header_content')
    <section class="home home--bg">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="home__title">{{ trans('nominations_film') }}</h1>
                    <button class="home__nav home__nav--prev" type="button">
                        <i class="icon ion-ios-arrow-round-back"></i>
                    </button>
                    <button class="home__nav home__nav--next" type="button">
                        <i class="icon ion-ios-arrow-round-forward"></i>
                    </button>
                </div>

                <div class="col-12">
                    <div class="owl-carousel home__carousel">
                    @foreach ($movieNominations as $movieNomination)
                        <div class="item">
                            <!-- card -->
                            <div class="card card--big">
                                <div class="card__cover">
                                    <img src="{{ asset($movieNomination['card_cover']) }}" alt="">
                                    <a href="{{ route('frontend.watchMovie', [$movieNomination['slug'], config('config.default_prioritize')]) }}" class="card__play">
                                        <i class="icon ion-ios-play"></i>
                                    </a>
                                </div>
                                <div class="card__content">
                                    <h3 class="card__title">
                                        <a href="{{ route('frontend.watchMovie',[$movieNomination['slug'], config('config.default_prioritize')]) }}">{{ $movieNomination['name'] }}</a>
                                    </h3>
                                    <h3 class="card__title">
                                        <a href="{{ route('frontend.watchMovie',[$movieNomination['slug'], config('config.default_prioritize')]) }}">({{ $movieNomination['name_origin'] }})</a>
                                    </h3>
                                    <span class="card__category">
                                        @foreach ($movieNomination['types'] as $type)
                                            <a href="{{ route('frontend.catalog',['type',$type['slug']]) }}">{{ trans($type['title']) }}</a>
                                        @endforeach
                                    </span>
                                    <span class="card__rate"><i class="icon ion-ios-star"></i>{{ $movieNomination['rate'] }}</span>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="content__head">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- content title -->
                        <h2 class="content__title">{{ trans('new_film') }}</h2>
                        <!-- end content title -->

                        <!-- content tabs nav -->
                        <ul class="nav nav-tabs content__tabs" id="content__tabs" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-1"
                                    aria-controls="tab-1" aria-selected="true">{{ trans('movies') }}</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tab-2" role="tab" aria-controls="tab-2"
                                    aria-selected="false">{{ trans('tv_series') }}</a>
                            </li>


                        </ul>
                        <!-- end content tabs nav -->

                        <!-- content mobile tabs nav -->
                        <div class="content__mobile-tabs" id="content__mobile-tabs">
                            <div class="content__mobile-tabs-btn dropdown-toggle" role="navigation" id="mobile-tabs"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <input type="button" value="New items">
                                <span></span>
                            </div>

                                    <li class="nav-item" data-value="movies">
                                        <a class="nav-link" id="1-tab"
                                            data-toggle="tab" href="#tab-1"
                                            role="tab" aria-controls="tab-1"
                                            aria-selected="true">{{ trans('movies') }}</a>
                                    </li>

                                    <li class="nav-item" data-value="tv series">
                                        <a class="nav-link" id="2-tab"
                                            data-toggle="tab" href="#tab-2"
                                            role="tab" aria-controls="tab-2"
                                            aria-selected="false">{{ trans('tv_series') }}</a>
                                    </li>


                                </ul>
                            </div>
                        </div>
                        <!-- end content mobile tabs nav -->
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- content tabs -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
                    <div class="row">
                    <!-- card -->
                    @foreach ($movies as $movie)
                        <div class="col-6 col-sm-12 col-lg-6">
                            <div class="card card--list">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="card__cover">
                                            <img src="{{ asset($movie['card_cover']) }}"
                                                alt="">
                                            <a href="{{ route('frontend.watchMovie', [$movie['slug'], config('config.default_prioritize')]) }}" class="card__play">
                                                <i class="icon ion-ios-play"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-8">
                                        <div class="card__content">
                                            <h3 class="card__title">
                                                <a href="{{ route('frontend.watchMovie',[$movie['slug'], config('config.default_prioritize')]) }}">{{ $movie['name'] }}</a>
                                            </h3>
                                            <h3 class="card__title">
                                                <a href="{{ route('frontend.watchMovie',[$movie['slug'], config('config.default_prioritize')]) }}">({{ $movie['name_origin'] }})</a>
                                            </h3>
                                            <span class="card__category">
                                            @foreach ($movie['types'] as $type)
                                                <a href="{{ route('frontend.catalog', ['type', $type['slug']]) }}">{{ trans($type['title']) }}</a>
                                            @endforeach
                                            </span>

                                            <div class="card__wrap">
                                                <span class="card__rate"><i class="icon ion-ios-star"></i>{{ $movie['rate'] }}</span>
                                                <ul class="card__list">
                                                    <li>{{ $movie['quality'] }}</li>
                                                    <li>{{ $movie['age'] }}+</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    <!-- end card -->
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-2" role="tabpanel" aria-labelledby="2-tab">
                    <div class="row">
                        @foreach ($tvSeries as $tvSerie)
                            <div class="col-6 col-sm-12 col-lg-6">
                                <div class="card card--list">
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="card__cover">
                                                <img src="{{ asset($tvSerie['card_cover']) }}" alt="">
                                                <a href="{{ route('frontend.watchMovie',[$tvSerie['slug'], config('config.default_prioritize')]) }}" class="card__play">
                                                    <i class="icon ion-ios-play"></i>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-8">
                                            <div class="card__content">
                                                <h3 class="card__title">
                                                    <a href="{{ route('frontend.watchMovie',[$tvSerie['slug'], config('config.default_prioritize')]) }}">{{ $tvSerie['name'] }}
                                                    </a>
                                                </h3>
                                                <h3 class="card__title">
                                                    <a href="{{ route('frontend.watchMovie',[$tvSerie['slug'], config('config.default_prioritize')]) }}">({{ $tvSerie['name_origin'] }})
                                                    </a>
                                                </h3>
                                                <span class="card__category">
                                                @foreach ($tvSerie['types'] as $type)
                                                    <a href="{{ route('frontend.catalog',['type',$type['slug']]) }}">{{ trans($type['title']) }}</a>
                                                @endforeach
                                                </span>

                                                <div class="card__wrap">
                                                    <span class="card__rate"><i class="icon ion-ios-star"></i>{{ $tvSerie['rate'] }}</span>
                                                    <ul class="card__list">
                                                        <li>{{ $tvSerie['quality'] }}</li>
                                                        <li>{{ $tvSerie['age'] }}+</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
            <!-- end content tabs -->
        </div>
    </section>
@endsection
