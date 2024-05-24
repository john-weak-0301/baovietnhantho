@extends('layouts.main')

@section('content')
    @if (request()->is(['tin-tuc', 'goc-chuyen-gia']))
        <h1 class="sr-only">{{ request()->is('tin-tuc') ? 'Tin tức' : 'Góc chuyên gia' }}</h1>

        @include('news.slider')
    @endif

    <section class="md-section">
        <div class="container">
            <div class="section-linear-gradient"></div>

            <div class="layout layout-sidebar--right">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="layout-content layout-content--border">
                            @if (isset($category))
                                <h1 class="section-title-min">{{ $category->name ?? 'Tin tức' }}</h1>
                            @endif

                            @foreach($news as $value)
                                <div class="new-list new-list__style-02">
                                    <div class="new-list__media">
                                        <img src="{{ $value->getImageUrl('medium') }}" alt="{{ esc_attr($value->title) }}">
                                    </div>

                                    <div class="new-list__body">
                                        <span class="new-list__cat">{!! $value->categories->pluck('name')->implode(', ') !!}</span>

                                        <h2 class="new-list__title">
                                            <a href="{{ $value->url }}">{!! $value->title ?? '' !!}</a>
                                        </h2>

                                        <p class="new-list__text">{!! $value->excerpt ?? '' !!}</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="pager">
                                {!! $news->onEachSide(1)->links('news.paginate') !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="layout-sidebar">
                            @include('news.category')
                            @include('news.top')
                            @include('news.banner')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
