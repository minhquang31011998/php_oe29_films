<div class="sidebar ">
    <!-- sidebar logo -->
    <a href="" class="sidebar__logo">
        <img src="{{ asset(config('config.logo_png')) }}" alt="">
    </a>
    <!-- end sidebar logo -->

    <!-- sidebar user -->
    <div class="sidebar__user">
        <div class="sidebar__user-img">
            <img src="" alt="">
        </div>

        <div class="sidebar__user-title">
            <span>{{ trans('admin') }}</span>
            <p></p>
        </div>

        <a class="sidebar__user-btn btn" href="" type="button">
           <i class="icon ion-ios-log-out"></i>
        </a>
    </div>
    <!-- end sidebar user -->

    <!-- sidebar nav -->
    <ul class="sidebar__nav scroll-y">
        <li class="sidebar__nav-item">
            <a href="" class="sidebar__nav-link"><i class="icon ion-ios-keypad"></i>{{ trans('dashboard') }}</a>
        </li>

        <li id="sidebar_title" class="sidebar__nav-item">{{ trans('user') }}</li>

        <li class="sidebar__nav-item">
            <a href="" class="sidebar__nav-link item-link"><i class="icon ion-ios-contacts"></i>{{ trans('user_list') }}</a>
        </li>

        <li class="sidebar__nav-item">
            <a href="" class="sidebar__nav-link item-link"><i class="icon ion-ios-chatbubbles"></i>{{ trans('comment_list') }}</a>
        </li>

        <li id="sidebar_title" class="sidebar__nav-item">{{ trans('movie') }}</li>

        <li class="sidebar__nav-item">
            <a href="" class="sidebar__nav-link item-link"><i class="icon ion-ios-film"></i>{{ trans('movie_list') }}</a>
        </li>

        <li class="sidebar__nav-item">
            <a href="" class="sidebar__nav-link item-link"><i class="icon ion-logo-youtube"></i>{{ trans('channel_list') }}</a>
        </li>

        <li class="sidebar__nav-item">
            <a href="" class="sidebar__nav-link item-link"><i class="icon ion-ios-play"></i>{{ trans('playlist_list') }}</a>
        </li>

        <li class="sidebar__nav-item">
            <a href="" class="sidebar__nav-link item-link"><i class="icon ion-ios-videocam"></i>{{ trans('video_list') }}</a>
        </li>
        <li class="sidebar__nav-item">
            <a href="" class="sidebar__nav-link"><i class="icon ion-ios-keypad"></i>{{ trans('type_list') }}</a>
        </li>
    </ul>
    <!-- end sidebar nav -->

    <!-- sidebar copyright -->
    <div class="sidebar__copyright">{{ config('config.user_created_by') }}</div>
    <!-- end sidebar copyright -->
</div>