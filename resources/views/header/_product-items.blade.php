<div class="row">
    @foreach ($productsWithCategory->chunk(2) as $columns)
        <div class="col-lg-4">
            @foreach ($columns as $categoryName => $products)
                <div class="list-menu">
                    <div class="list-menu__title">
                        <a href="{{ isset($products[0]->categories[0]) ? route('product.category', $products[0]->categories[0]->slug) : '' }}">{{ $categoryName }}</a>
                    </div>

                    <ul class="list-menu__list">
                        @foreach ($products as $product)
                            <li><a href="{{ $product->url }}">{{ $product->title }}</a></li>
                        @endforeach
                    </ul>
                </div><!-- End / list-menu -->
            @endforeach
        </div>
    @endforeach
</div>
