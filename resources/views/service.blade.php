@extends('layouts.main')

@section('content')
    <section class="page-title" style="background-image: url({!! $service->image !!});">
        <div class="page-title__wrapper">
            <div class="container">
                <h1 class="page-title__title">{{ $service->title }}</h1>
                {{ Breadcrumbs::view('breadcrumbs', 'service', $service) }}
            </div>
        </div>
    </section>

    @php
        $content = $service->getContent();
        $wrapInContainer = false;

        if (false === strpos($content, '<!-- wp:core/section')) {
            $wrapInContainer = true;
        }
    @endphp

    @if($wrapInContainer)
        <div class="md-section"><div class="container">
    @endif

        <div class="layout layout-no-sidebar">
            <div class="layout-content">
                <div class="layout-content__entry">
                    <div class="layout-content__detail">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </div>

    @if($wrapInContainer)
        </div></div>
    @endif
@endsection

@push('styles')
    <style>
        .group-cols {
            background: #e0e0e0;
            font-weight: bold;
        }
        .first-row {
            width: 10% !important;
        }
        td.child-row {
            color: #8e8e8e;
        }
        th.child-row {
            text-transform: unset !important;
        }
        .table-phamviapdung {
            margin-top: 30px;
        }
        .text-label_1 {
            font-size: 30px;
        }
        .statistic-block {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 80px;
        }
        .solution-icon__title {
            text-transform: unset !important;
        }
        .solution-icon__list {
            width: 60%;
            margin: auto;
        }
        .fund-name_1 {
            text-transform: capitalize;
        }
        .next-period {
            margin-top: 40px;
        }
    </style>
@endpush
