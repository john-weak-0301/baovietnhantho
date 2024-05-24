@extends('layouts.main')

@section('content')
    <div id="tool-root"></div>
@endsection

@push('scripts')
    <script>
        window._financePlanStatic = @json($data);
    </script>

    <script src="{{ mix('js/tool.js') }}"></script>
@endpush
