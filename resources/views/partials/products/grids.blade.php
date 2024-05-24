<div class="row row-eq-height">
    @if (!blank($products))
        @php
            $products->loadMissing('category');
        @endphp

        @foreach ($products as $product)
            <div class="col-sm-6 col-md-6 col-lg-4">
                @include('partials.products.grid', compact('product'))
            </div>
        @endforeach
    @endif
</div>
