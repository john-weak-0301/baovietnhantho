@extends('layouts.main')

@section('content')
    @if(!blank($sliders))
        @include('partials.slider', compact('sliders'))
    @endif

    <section class="md-section section-category-icon">
        <div class="container">
            @include('home.categoryIcon')
        </div>
    </section>

    {!! $page->getContent() !!}
@endsection
