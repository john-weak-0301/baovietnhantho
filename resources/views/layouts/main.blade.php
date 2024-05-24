<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='dns-prefetch' href='//fonts.googleapis.com' />
    <meta name="csrf-token" content="{{ csrf_token() }}" id="csrf-token">
    @php
        $title = $__env->yieldContent('title', $title ?? null);
        if (isset($title) && $title) {
            SEO::setTitle($title); // Bảo Việt Nhân Thọ
        }
    @endphp

    {!! SEO::generate() !!}
    @yield('meta')

    {{--<link rel="stylesheet" href="https://use.typekit.net/mcq6uph.css">--}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700">
    <link rel="stylesheet" href="/fonts/fontawesome/font-awesome.min.css">
    @stack('links')

    <link rel="stylesheet" href="/css/vendor.css">
    <link rel="stylesheet" href="{{ mix('css/main.css') }}">
    <link rel="stylesheet" href="/css/blocks.css">
    @stack('styles')

    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({
            'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-NR2LRSP');
    </script>

    <script type='application/ld+json'>
    {
      "@context": "http://www.schema.org",
      "@type": "InsuranceAgency",
      "name": "Bảo Việt Nhân Thọ",
      "url": "https://www.baovietnhantho.com.vn/",
      "logo": "https://www.baovietnhantho.com.vn/img/logo.png",
      "image": "https://baovietnhantho.com.vn/storage/8e4d7d02-7c6d-441e-b9c4-b7fc728dd7f6/c/tru-so-bao-viet-nhan-tho-large.jpg",
      "description": "Bảo Việt Nhân Thọ là Công ty bảo hiểm nhân thọ số 1 tại Việt Nam, mang theo sứ mệnh đem lại một cuộc sống an lành và hạnh phúc cho người Việt.",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Tầng 37, Keangnam Ha Noi Landmark Tower, Đường Phạm Hùng, Quận Nam Từ Liêm, Hà Nội",
        "addressLocality": "Hà Nội",
        "postalCode": "100000",
        "addressCountry": "Việt Nam"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": "21.016861",
        "longitude": "105.784173"
      },
      "openingHours": "Mo, Tu, We, Th, Fr 08:00-17:00",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "1900 558899",
        "contactType": "Customer service"
      }
    }
    </script>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NR2LRSP" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    @include('partials.popup')
    <div class="page-wrap" id="root">
        <div class="fixheight-header"></div>

        <header class="header" id="header">
            @include('partials.header')
        </header>

        <div class="header-search">
            @include('partials.header-search')
        </div>

        @include('partials.mobile-menu')

        @if (!request()->is('/'))
            @include('partials.header-toolbox')
        @endif

        <div class="md-content">
            @yield('content')
        </div>

        <div id="footer">
            @include('partials.footer')
        </div>
    </div>

    <script src="/js/vendor.js?_ver=1570509004"></script>
    <script>window.lodash = _.noConflict();</script>
    <script src="{{ mix('js/main.js') }}"></script>
    @stack('scripts')

    @include('partials.customer-chat')
</body>
</html>
