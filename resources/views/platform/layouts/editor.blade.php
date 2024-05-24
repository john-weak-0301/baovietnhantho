<!DOCTYPE html>
<html lang="{{  app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bảo Việt Nhân Thọ') - @yield('description', 'Sửa nội dung')</title>
    <meta name="csrf_token" content="{{  csrf_token() }}" id="csrf_token">
    <link rel="stylesheet" type="text/css" href="{{  orchid_mix('/css/orchid.css','orchid') }}">
    <link rel="stylesheet" href="{{ mix('css/dashboard.css', '/vendor/dashboard') }}">
    <link rel="stylesheet" href="https://developer.wordpress.org/wp-includes/css/dashicons.min.css">
    @stack('head')

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

    <script>window.wp = window.wp || {};</script>
    <script src="{{ asset('vendor/dashboard/js/wp-media.js') }}"></script>

    <script>
        (function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
                },
            });

            axios.get('/dashboard/resource/media-template').then(function(response) {
                if (response.data) {
                    document.head.innerHTML += response.data;
                }
            });
        })();
    </script>

    <script src="https://unpkg.com/react@16.8.4/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@16.8.4/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/moment@2.22.1/min/moment.min.js"></script>

    <script src="https://unpkg.com/lodash@4.17.14/lodash.min.js"></script>
    <script>window.lodash = _.noConflict();</script>

    @foreach(Dashboard::getResource('stylesheets') as $stylesheet)
        <link rel="stylesheet" href="{{  $stylesheet }}">
    @endforeach

    @stack('stylesheets')

    @foreach(Dashboard::getResource('scripts') as $scripts)
        <script src="{{  $scripts }}" type="text/javascript"></script>
    @endforeach
</head>
<body class="wp-embed-responsive svg">

    <div id="app">
        @yield('body')
    </div>

    @stack('scripts')
</body>
</html>
