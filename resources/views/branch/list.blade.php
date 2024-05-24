@extends('layouts.main')

@section('title', 'Chi nhánh' )

@section('content')
    <h1 class="sr-only">Chi nhánh</h1>

    <section class="md-section branch-map">
        <div id="branch-locator"></div>
    </section>
@endsection

@push('scripts')
    <script>window._staticBranchs = [];</script>
    <script>window._googleMapApiKey = '{{ config('baoviet.google_maps_api_key') }}';</script>
    <script src="{{ mix('js/branch.js') }}"></script>
@endpush
