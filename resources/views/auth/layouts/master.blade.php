<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{asset('bower_components/bower_film/backend/css/bootstrap-reboot.min.css')}}">
    <link rel="stylesheet" href="{{asset('bower_components/bower_film/backend/css/bootstrap-grid.min.css')}}">
    <link rel="stylesheet" href="{{asset('bower_components/bower_film/backend/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('bower_components/bower_film/backend/css/jquery.mCustomScrollbar.min.css')}}">
    <link rel="stylesheet" href="{{asset('bower_components/bower_film/backend/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('bower_components/bower_film/backend/css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('bower_components/bower_film/backend/css/admin.css')}}">

    <link rel="icon" type="image/png" href="{{ asset(config('config.icon_favicon')) }}" sizes="32x32">
    <title>@yield('title')</title>

</head>
<body>
    <div class="sign section--bg" data-bg="bower_components/bower_film/img/section/section.jpg">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sign__content">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('bower_components/bower_film/backend/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('bower_components/bower_film/backend/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('bower_components/bower_film/backend/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('bower_components/bower_film/backend/js/jquery.mousewheel.min.js')}}"></script>
    <script src="{{asset('bower_components/bower_film/backend/js/jquery.mCustomScrollbar.min.js')}}"></script>
    <script src="{{asset('bower_components/bower_film/backend/js/select2.min.js')}}"></script>
    <script src="{{asset('bower_components/bower_film/backend/js/admin.js')}}"></script>
</body>
</html>
