@extends('frontend.layouts.master')

@section('title'){{ $movie['name_origin'] }}[{{ $movie['quality'] }}]@endsection

@section('header_content')
    <section class="section details">
        <!-- details background -->
        <div class="details__bg" data-bg=""></div>
        <!-- end details background -->

        <!-- details content -->
        <div class="container">
            <div class="row">
                <!-- title -->
                <div class="col-12">
                    <h1 class="details__title">{{ $movie['name'] }}</h1>
                    <h1 class="details__title">({{ $movie['name_origin'] }})</h1>
                </div>
                <!-- end title -->

                <!-- content -->
                <div class="col-10">
                    <div class="card card--details card--series">
                        <div class="row">
                            <!-- card cover -->
                            <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                                <div class="card__cover">
                                    <img src="{{ asset($movie['card_cover']) }}" alt="">
                                </div>
                            </div>
                            <!-- end card cover -->

                            <!-- card content -->
                            <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-xl-9">
                                <div class="card__content">
                                    <div class="card__wrap">
                                        <span class="card__rate"><i class="icon ion-ios-star"></i>{{ $movie['rate'] }}</span>
                                        <ul class="card__list">
                                            <li>{{ $movie['quality'] }}</li>
                                            <li>{{ $movie['age'] }}+</li>
                                        </ul>
                                    </div>

                                    <ul class="card__meta">
                                        <li><span>{{ trans('type') }}:</span>
                                        @foreach ($types as $type)
                                            <a href="{{route('frontend.catalog', ['type',$type['slug']]) }}">{{ trans($type['title']) }}</a>
                                        @endforeach
                                        <li><span>{{ trans('release_year') }}:</span> {{ $movie['release_year'] }}</li>
                                        <li>
                                            <span>{{ trans('running_time') }}:</span> {{ $movie['runtime'] . " " . trans('minute') }}
                                        </li>
                                        <li><span>{{ trans('country') }}:</span>
                                            <a href="{{ route('frontend.catalog', ['$country', $movie['country']]) }}">{{ trans($movie['country']) }}</a>
                                        </li>
                                    </ul>

                                    <div class="card__description card__description--details">
                                        {{ $movie['description'] }}
                                    </div>
                                </div>
                            </div>
                            <!-- end card content -->
                        </div>
                    </div>
                </div>
                <!-- end content -->

                <!-- player -->
                <div class="col-12">
                    <div class="video_player">
                        @if ($channel['channel_type'] == config('config.default_server'))
                            <iframe width="1110" height="630" src="{{ config('config.scr_ytb_default') . $source['source_key'] }}" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        @else
                            <video width="1110" height="630" controls  id="player" autoplay>
                                <source src="{{ asset($source['source_key']) }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                </div>
                <div class="backup col-12">
                    <span><a class="btn">{{ $video['tags'] }}</a></span>
                </div>
                <div class="backup col-12 col-xl-6">
                    <div class="backup_title"><span>{{ trans('backup_link')}}:</span></div>
                    @foreach ($backups as $backup)
                        <span @if ($backup['prioritize'] == $source['prioritize']) hidden @endif>
                            <a href="{{ route('frontend.watchMovie', [$movie['slug'], $backup['prioritize']]) }}" class="btn">#{{ $backup['title'] . '_' . $backup['prioritize'] }}</a>
                        </span>
                    @endforeach
                    </div>
                <div class="backup col-12">
                    <div class="backup_title"><span>{{ trans('tags')}}:</span></div>
                    @foreach ($tags as $tag)
                        <span><a href="{{ route('frontend.searchTag', [$tag['slug']])}}" class="btn">{{ $tag['name'] }}</a></span>
                    @endforeach
                </div>
                <!-- end player -->
                @if (auth()->check())
                    <div class="stars">
                        <form action="" method="post" id="rate_form">
                            @csrf
                            <input class="star star-5" id="star-5" type="radio" name="star" value="5"/>
                            <label class="star star-5" for="star-5"></label>
                            <input class="star star-4" id="star-4" type="radio" name="star" value="4"/>
                            <label class="star star-4" for="star-4"></label>
                            <input class="star star-3" id="star-3" type="radio" name="star" value="3"/>
                            <label class="star star-3" for="star-3"></label>
                            <input class="star star-2" id="star-2" type="radio" name="star" value="2"/>
                            <label class="star star-2" for="star-2"></label>
                            <input class="star star-1" id="star-1" type="radio" name="star" value="1"/>
                            <label class="star star-1" for="star-1"></label>
                            <input type="hidden" name="movie_id" id="movie_id" value="{{ $movie['id'] }}">
                            <button type="submit" name="submit" class="form__btn">{{ trans('rate') }}</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
        <!-- end details content -->
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="content__head">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <!-- content title -->
                        <h2 class="content__title">{{ trans('comments')}}</h2>
                        <!-- end content title -->

                        <!-- content tabs nav -->
                        <ul class="nav nav-tabs content__tabs" id="content__tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tab-1" role="tab"
                                    aria-controls="tab-1" aria-selected="true">{{ trans('comments')}}</a>
                            </li>

                        </ul>
                        <!-- end content tabs nav -->

                        <!-- content mobile tabs nav -->
                        <div class="content__mobile-tabs" id="content__mobile-tabs">
                            <div class="content__mobile-tabs-btn dropdown-toggle" role="navigation" id="mobile-tabs"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <input type="button" value="Comments">
                                <span></span>
                            </div>

                            <div class="content__mobile-tabs-menu dropdown-menu" aria-labelledby="mobile-tabs">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" data-value="comments">
                                        <a class="nav-link active" id="1-tab"
                                            data-toggle="tab" href="#tab-1"
                                            role="tab" aria-controls="tab-1"
                                            aria-selected="true">{{ trans('comments')}}</a>
                                    </li>
                            </div>
                        </div>
                        <!-- end content mobile tabs nav -->
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 col-xl-8">
                    <!-- content tabs -->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tab-1" role="tabpanel" aria-labelledby="1-tab">
                            <div class="row">
                                <!-- comments -->
                                <div class="col-12">
                                    <div class="comments">
                                        @if (auth()->check())
                                            <form action="{{route('frontend.comment.store')}}" class="form" method="POST" id="comment_form">
                                                @csrf
                                                <span class="comments__name" id="comment_message"></span>
                                                <meta name="csrf-token" content="{{ csrf_token() }}" />
                                                <textarea id="text" name="content" id="comment_content" class="form__textarea comment_content"
                                                    placeholder="{{ trans('add_comment') }}"></textarea>
                                                <input type="hidden" name="comment_id" id="comment_id" value="">
                                                <input type="hidden" name="movie_id" id="movie_id" value="{{ $movie['id'] }}">
                                                <button type="submit" name="submit" id="submit" class="form__btn">{{ trans('send') }}</button>
                                            </form>

                                            <ul class="comments__list" id="display_comment">
                                            </ul>
                                        @else
                                            <span class="comments__name">{{ trans('read_comment') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- end comments -->
                            </div>
                        </div>
                    </div>
                    <!-- end content tabs -->
                </div>
            </div>
        </div>
    </section>
@endsection
