@if (count($breadcrumbs))
    <ul class="page-title__breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

            @if ($breadcrumb->url && !$loop->last)
                <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
            @else
                <li><span>{{ $breadcrumb->title }}</span></li>
            @endif

        @endforeach
    </ul>
@endif
