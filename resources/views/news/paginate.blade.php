@if ($paginator->hasPages())
    <ul class="pager__ul">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="pager__page pager-custom">
                <span class="page-link" aria-hidden="true">&lsaquo; Trang trước</span>
            </li>
        @else
            <li class="pager__page pager-custom">
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">{!! __('&lsaquo;  Trang trước') !!}</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="pager__page"><a href="#" style="pointer-events: none"><span>{{ $element }}</span></a></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="pager__page is-active"><a href="#">{{ $page }}</a></li>
                    @else
                        <li class="pager__page"><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="pager__page pager-custom">
                <a href="{{ $paginator->nextPageUrl() }}">{!! __('Trang sau') !!} &rsaquo;</a>
            </li>
        @else
            <li class="pager__page pager-custom">
                <a>Trang sau &rsaquo;</a>
            </li>
        @endif
    </ul>
@endif
