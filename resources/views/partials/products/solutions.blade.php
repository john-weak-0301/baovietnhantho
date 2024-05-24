<div class="row row-eq-height">
    @foreach($products as $product)
        <div class="col-md-4 ">
            @include('partials.products.grid', compact('product'))
        </div>
    @endforeach
</div>
