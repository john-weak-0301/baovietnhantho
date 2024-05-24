<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bảo Việt Nhân Thọ') - @yield('description', 'Sửa nội dung')</title>
    <meta name="csrf_token" content="{{  csrf_token() }}" id="csrf_token">

    <link rel="stylesheet" href="{{ asset('vendor/block-editor/block-editor.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/wp-media/media-views.css') }}">
    <link rel="stylesheet" href="https://developer.wordpress.org/wp-includes/css/dashicons.min.css">

    <script src="/vendor/jquery/jquery-1.12.4.min.js"></script>
    <script src="/vendor/react/react.development.js"></script>
    <script src="/vendor/react/react-dom.development.js"></script>

    <script src="/vendor/axios/axios.min.js"></script>
    <script src="/vendor/lodash/lodash.min.js"></script>
    <script>window.lodash = _.noConflict();</script>

    <script>window.wp = window.wp || {};</script>
    <script src="{{ asset('vendor/dashboard/js/wp-media.js') }}"></script>

    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>
</head>
<body class="wp-embed-responsive svg">

    <div id="editor" class="hide-if-no-js"></div>
    <script defer="defer" src="{{ asset('vendor/block-editor/block-editor.js?t=' . time()) }}"></script>

    @php
        wp_print_media_templates();
    @endphp
</body>
</html>
