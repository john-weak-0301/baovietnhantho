<div class="widget">
    <div class="widget__title">{{ __('Được xem nhiều nhất') }}</div>

    <ul class="widget-post">
        @foreach($topNews as $value)
            <li>
                <a class="widget-post__title" href="{{ $value->url }}">{{ $value->title }}</a>
            </li>
        @endforeach
    </ul>
</div>
