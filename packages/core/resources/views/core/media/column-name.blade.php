<strong class="has-media-icon">
    <a href="#">
        <span class="media-icon image-icon">{!! $media->img('thumbnail') !!}</span>
        <span class="">{{ $media->name ?: '(no name)' }}</span>
    </a>
</strong>

<p class="filename text-gray mb-1">
    <span class="sr-only">{{ __('File name:') }}</span>
    {{ $media->file_name ?? '' }}
</p>
