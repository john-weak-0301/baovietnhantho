@extends('layouts.main')

@section('title', 'So sánh sản phẩm')

@section('content')
    <section class="md-section">
        <div class="container">
            <div id="compare-products">
                <compare :attributes='@json($compareAttributes)'></compare>
            </div>
        </div>
    </section>
@stop

@push('scripts')
    <script>
        window._compareData = @json([
            'compareProducts' => $compareProducts,
            'productsWithCategory' => $productsWithCategory,
        ]);
    </script>

    <script src="{{ mix('js/compare.js') }}"></script>
@endpush
