@extends('layouts.main')

@section('content')
    @includeWhen($page->title && $page->options->get('show_page_title'), 'partials.page-title', [
        'title' => $page->title ?? '',
        'image' => $page->options->get('title_image') ?? '',
        'style' => $page->options->get('style') ?? '',
        'overlay' => true,
    ])

    @if($path = request()->path())
        @includeIf('pages.header-'.$path)
    @endif

    @if (!blank($anchors))
        <section class="md-section pt-0 pb-0 z-index-initial">
            <div class="menubox-min-section-wrap">
                <div class="menubox-min-fix"></div>

                <div class="menubox-min-section">
                    <div class="container">
                        <div class="menubox-min nav-wrap-scroll">

                            <ul class="menubox-min__list one-page-nav">
                                @foreach ($anchors as $anchor)
                                    <li>
                                        <a href="{{ $anchor['anchorURL'] ?: '#anchor-'.$anchor['id'] }}">{{ $anchor['anchor'] }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="menubox-min__fix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {!! $page->getContent() !!}

    @if($path = request()->path())
        @includeIf('pages.footer-'.$path)
    @endif
@endsection
