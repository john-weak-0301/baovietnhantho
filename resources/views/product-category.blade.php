@extends('layouts.main')

@section('content')
    @php
        $showPageTitle = $category->name && $category->options->get('show_page_title');
    @endphp

    @includeWhen($showPageTitle, 'partials.page-title', [
        'title' => $category->name ?? '',
        'image' => $category->options->get('page_title_image') ?? '',
        'style' => $category->options->get('page_title_style') ?? '',
        'overlay' => true,
    ])

    @if(!$showPageTitle)
        <h1 class="sr-only">{{ $category->name ?? 'Sản phẩm' }}</h1>
    @endif

    @includeIf('pages.header-san-pham')

    <section class="md-section">
        <div class="container">

            @if ($category->subtitle || $category->description)
                <div class="row">
                    <div class="col-lg-9 ">
                        <div class="title title__title-page">
                            @if ($category->subtitle)
                                <div class="title__title">{{ $category->subtitle }}</div>
                            @endif

                            @if ($category->description)
                                <div class="title__text">{!! wpautop($category->description) !!}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-20">
                <div class="section-title-min h2">Các sản phẩm "{{ $category->name }}"</div>
            </div>

            @include('partials.products.grids', compact('products'))

            <div class="pager">
                {!! $products->onEachSide(1)->links('news.paginate') !!}
            </div>
        </div>
    </section>

    <section class="md-section" style="padding-top: 0;">
        <div class="container">
            <div class="mt-20">
                <h2 class="section-title-min">Các sản phẩm bổ trợ</h2>
            </div>

            {!! do_shortcode('[SPBT]') !!}
        </div>
    </section>
@endsection
