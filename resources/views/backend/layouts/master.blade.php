<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/bootstrap-reboot.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/bootstrap-grid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/jquery.mCustomScrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/backend/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('build/backend/css/film.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bower_film/font-awesome-4.7.0/css/font-awesome.min.css') }}">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset(config('config.icon_favicon')) }}" sizes="32x32">

    @yield('css')

    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <title>@yield('title')</title>

</head>
<body>
    <!-- header -->
    @include('backend.layouts.header')
    <!-- end header -->
    <!-- sidebar -->
    @include('backend.layouts.sidebar')
    <!-- end sidebar -->
    <!-- main content -->
    <main class="main">
        <div class="container-fluid">
            <div class="row">
                <!-- main title -->
                <div class="col-12">
                    <div class="main__title">
                        @yield('main__title')

                        {{-- @yield('main__title') --}}
                    </div>
                </div>
                <!-- end main title -->

                <!-- users -->
                @yield('content')
                {{-- @yield('content') --}}
            </div>
        </div>
    </main>
    <!-- end main content -->
    @yield('modal')
    <!-- modal status -->

    <!-- end modal delete -->

    <!-- JS -->
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.mousewheel.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/jquery.mCustomScrollbar.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/select2.min.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/admin.js') }}"></script>
    <script src="{{ asset('bower_components/bower_film/backend/js/sweetalert.min.js') }}"></script>

    @yield('js')

    @include('sweetalert::alert')

</body>
</html>
