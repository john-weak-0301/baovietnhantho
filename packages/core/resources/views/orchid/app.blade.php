<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-controller="layouts--html-load">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bảo Việt Nhân Thọ') - @yield('description', 'Dashboard')</title>

    <meta name="auth" content="{{ Auth::check() }}" id="auth" data-turbolinks-permanent>
    <meta name="csrf_token" content="{{ csrf_token() }}" id="csrf_token" data-turbolinks-permanent>
    <meta name="turbolinks-visit-control" content="reload">

    <meta name="turbolinks-root" content="{{ Dashboard::prefix() }}">
    <meta name="dashboard-prefix" content="{{ Dashboard::prefix() }}">
    @stack('head')

    <link rel="stylesheet" type="text/css" href="{{  orchid_mix('/css/orchid.css','orchid') }}">
    <link rel="stylesheet" href="{{ mix('css/dashboard.css', '/vendor/dashboard') }}">
    {{--<link rel="stylesheet" href="https://unpkg.com/@icon/dashicons/dashicons.css">--}}
    @stack('stylesheets')

    <script src="{{ orchid_mix('/js/manifest.js','orchid') }}" type="text/javascript" data-turbolinks-permanent></script>
    <script src="{{ orchid_mix('/js/vendor.js','orchid') }}" type="text/javascript" data-turbolinks-permanent></script>
    <script src="{{ orchid_mix('/js/orchid.js','orchid') }}" type="text/javascript" data-turbolinks-permanent></script>
    <script src="{{ mix('js/dashboard.js', '/vendor/dashboard') }}" data-turbolinks-permanent></script>
    <script defer src="{{ asset('vendor/links-picker/index.js?time=' . time()) }}" data-turbolinks-permanent></script>
    @stack('scripts')

    <script src="{{ asset('vendor/dashboard/js/wp-media.js') }}" data-turbolinks-permanent></script>
    <script>
        $(function () {
            axios.get('/dashboard/resource/media-template').then(function (response) {
                if (response.data) {
                    document.getElementById('js-media-templates').innerHTML = response.data;
                }
            });
        });
    </script>

    <style>
        .table-responsive .table {
            table-layout: fixed;
        }
    </style>
</head>
<body data-turbolinks="false">

    <div class="app row m-n" id="app" data-controller="@yield('controller')" @yield('controller-data')>
        <div class="container-fluid">
            <div class="row">
                <aside class="aside col-xs-12 col-md-12 col-xl-2 col-xxl-3 no-padder bg-dark">
                    <div class="d-md-flex align-items-start flex-column d-sm-block h-full">
                        @yield('body-left')
                    </div>
                </aside>

                <main class="main col-xs-12 col-md-12 col-xl-8 col-xxl-8 bg-white b-r shadow-lg no-padder min-vh-100">
                    @yield('body-right')
                </main>
            </div>
        </div>
    </div>

    @stack('templates')

    <div id="js-media-templates"></div>
</body>
</html>
