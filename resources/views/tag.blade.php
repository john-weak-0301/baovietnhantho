@extends('layouts.main')

@section('content')
    <section class="md-section">
        <div class="container">

            <h1>{{ $title }}</h1>

            <div class="search-page__list">
                <ul>
                    @foreach($tagged as $model)
                        <li>
                            <h2 class="search-page__listitle">
                                <a href="{{ $model->url }}">{{ $model->title ?? $model->name ?? '' }}</a>
                            </h2>

                            <div>
                                {!! wpautop($model->excerpt ?? $model->description ?? '') !!}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </section>
@endsection
