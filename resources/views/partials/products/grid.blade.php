<div class="new">
    <div class="new__inner">
        <div class="new__media" style="background-image: url({{ $product->image ?? '/img/default.png' }});">
            <img src="{{ $product->image ?? '/img/default.png' }}" alt="{{ esc_attr( $product->title ?? '' ) }}" />

            @if($cate = $product->category)
                <div class="new__catwrap">
                    <a class="new__cat" href="{{ route('product.category', $cate->slug) }}">{{ $cate->name }}</a>

                    @if ($cate->parent_id && $_cate = $cate->parent)
                        <a class="new__cat" href="{{ route('product.category', $_cate->slug) }}">{{ $_cate->name }}</a>
                    @endif
                </div>
            @endif
        </div>

        <div class="new__body">
            @if (request()->is('san-pham/danh-muc/*'))
                <h2 class="new__title">
                    <a href="{{ $product->url }}">{{ $product->title ?? '' }}</a>
                </h2>
            @else
                <h3 class="new__title">
                    <a href="{{ $product->url }}">{{ $product->title ?? '' }}</a>
                </h3>
            @endif

            <p class="new__text">{{ $product->excerpt ?? '' }}</p>
            <a class="btn btn-secondary" href="{{ $product->url }}">{{ __('Tìm hiểu ngay') }}</a>
        </div>
    </div>
</div>
