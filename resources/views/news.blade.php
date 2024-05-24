@extends('layouts.blog')

@section('main')
    <div class="layout-content">
        <div class="new-detail">
            <div class="new-detail__metatop">
                <span class="meta-cat">{!! $news->categories->pluck('name')->first() ?? '' !!}</span>
                <span class="meta-date">
                    @if ($news->published_date)
                        {!! $news->published_date->format('d/m/Y') ?? ''; !!}
                    @else
                        {!! $news->created_at->format('d/m/Y') ?? ''; !!}
                    @endif
                </span>
            </div>

            <h1 class="new-detail__title">{{ $news->title }}</h1>

            <div class="new-detail__meta">
                <div class="new-detail__social dropdown">
                    <span>Chia sẻ</span>

                    <a class="icon-share" href="#shareModal" id="openShareModal" data-toggle="modal" data-target="#shareModal" aria-haspopup="true" title="Chia sẻ">
                        <i class="fa fa-facebook"></i>
                    </a>
                </div>
            </div>

            <div class="new-detail__entry">
                @isset($tocs)
                    <div class="layout-content__toc">
                        <p class="layout-content__toc-heading">Nội dung bài viết</p>

                        {!! $tocs !!}
                    </div>
                @endisset

                <div class="layout-content__detail">
                    {!! $news->getContent() ?? '' !!}
                </div>
            </div>

            @if (isset($related) && !blank($related))
                <div class="layout-content__itembox">
                    <div class="section-title-min">{!! __('Bài viết liên quan') !!}</div>

                    <ul class="new-detail__relate">
                        @foreach ($related as $post)
                            <li>
                                <a href="{{ $post->url }}">{{ $post->title ?? '' }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="new-detail__tag">
                @if (!blank($tags = $news->tags))
                    <div class="new-detail__taginner">
                        <span>{{ __('Từ khoá:') }}</span>

                        @foreach($tags as $tag)
                            <a href="{{ route('tags', $tag->slug) }}">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                @endif

                <div class="new-detail__social new-detail__social--inline">
                    <span>Chia sẻ</span>

                    <a class="icon-share" href="#shareModal" id="openShareModalFooter" data-toggle="modal" data-target="#shareModal" aria-haspopup="true" title="Chia sẻ">
                        <i class="fa fa-facebook"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('sidebar')
    @include('news.category')
    @include('news.top')
    @include('news.banner')
@endsection

@section('content')
    @parent

    @if (!blank($inCategory))
        <section class="md-section bg-gray">
            <div class="container">
                <div class="section-title-min">{{ __('Các bài viết cùng chủ đề') }}</div>

                <div class="row row-eq-height">
                    @foreach ($inCategory as $_news)
                        <div class="col-sm-6 col md-6 col-lg-3">

                            <div class="new-02">
                                <div class="new-02__media" style="background-image: url('{{ $_news->getImageUrl('large') }}');">
                                    <img src="{{ $_news->getImageUrl('large') }}" alt="{{ esc_attr($_news->title) }}">
                                </div>

                                <div class="new-02__body">
                                    <a class="new-02__title" href="{{ $_news->url }}" title="{{ $_news->title }}">{{ Str::words($_news->title, 20) }}</a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    @include('partials.share')

    <script>
      (function() {
        try {
          new bsn.Modal(document.getElementById('openShareModalFooter'));
        } catch (e) {}
      })();
    </script>
@endpush
