<div class="row row-eq-height">
    @foreach($products as $product)
        <div class="col-xsx-12 col-xs-6 col-md-4 col-lg-3">
            <div class="text-box">
                <a class="text-box__inner" href="{{ $product->url }}" style="display: block;">
                    <h3 class="text-box__title">{{ $product->title }}</h3>
                    <p class="text-box__text">{{ $product->excerpt }}</p>
                    <span class="text-box__btn" role="button"><img src="/img/icon/add.svg" alt="Xem"></span>
                </a>
            </div>
        </div>
    @endforeach
</div>
