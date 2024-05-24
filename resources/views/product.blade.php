@extends('layouts.main')

@section('content')
    @includeWhen($product->options->show_page_title ?? true, 'partials.page-title', [
        'title' => $product->title ?? '',
        'image' => $product->options->page_title_image,
        'style' => $product->options->page_title_style,
        'overlay' => true,
    ])

    @if (isset($anchors))
        <section class="md-section pt-0 pb-0 z-index-initial">
            <div class="menubox-min-section-wrap">
                <div class="menubox-min-fix"></div>

                <div class="menubox-min-section">
                    <div class="container">
                        <div class="menubox-min nav-wrap-scroll">
                            <ul class="menubox-min__list one-page-nav">
                                <li><a href="" class="keep-height"></a></li>

                                @foreach ($anchors as $anchor)
                                    <li>
                                        <a href="{{ $anchor['anchorURL'] ?: '#anchor-'.$anchor['id'] }}">{{ $anchor['anchor'] }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="menubox-min__fix"></div>
                            <ul class="menubox-min__tool">
                                <li class="dropdown">
                                    <a class="menubox-min__btn" href="#shareModal" id="openShareModal" data-toggle="modal" data-target="#shareModal" aria-haspopup="true">
                                        <img src="/img/svg/chiase.svg" alt="Chia sẻ">
                                        Chia sẻ
                                    </a>
                                </li>

                                <li>
                                    <a class="menubox-min__btn" href="/san-pham/so-sanh/?p={{ $product->id }}">
                                        <img src="/img/svg/sosanh.svg" alt="So sánh">So sánh
                                    </a>
                                </li>

                                <li>
                                    <a class="btn btn-secondary" href="/tu-van">Đăng ký</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {!! $product->getContent() !!}

    @if (isset($relatedProducts) && !blank($relatedProducts))
        <section class="md-section">
            <div class="container">
                <h2 class="section-title-min">Sản phẩm có thể bạn quan tâm</h2>

                @include('partials.products.grids', ['products' => $relatedProducts])
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    @include('partials.share')
@endpush
