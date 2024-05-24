<div class="container">
    <div class="row">
        <div class="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">
            <form class="header-search__form" method="GET" action="{{ route('search') }}" role="search">
                <input class="form-control" type="text" name="q" placeholder="{{ __('Nhập thông tin cần tìm kiếm…') }}" autofocus/>
                <span class="form-icon"><i class="fa fa-search"></i></span><span class="form-close"><i class="fa fa-close"></i></span>
            </form>

            @if ($popularTags = setting('popular_tags'))
                <div class="header-search__keyword">
                    <div class="header-search__titlemin">{{ __('Từ khoá phổ biến') }}</div>
                    <ul class="header-search__listkey">
                        @foreach (explode("\n", $popularTags) as $tag)
                            <li><a href="{{ route('search', ['q' => trim(rawurlencode($tag))]) }}">{{ trim($tag) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            var $header = $('.header-search');

            $(document).on('keyup', function(e) {
                if (e.key === 'Escape' && $header.hasClass('header-search--active')) {
                    $header.find('.form-close').trigger('click');
                }
            });
        })();
    </script>
@endpush
