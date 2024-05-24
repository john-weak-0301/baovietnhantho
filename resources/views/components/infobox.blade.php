<div class="contact-infobox">
    <div class="contact-infobox__inner {{ $class ?? '' }}">
        <img src="{{ $image ?? '' }}" alt="{{ esc_attr($title ?? '') }}">
        <h5 class="contact-infobox__title">{{ $title ?? '' }}</h5>

        <p class="contact-infobox__text">{!! $description !!}</p>
        <a class="btn btn-light" href="{{ $link ?? '#' }}">{{ $link_text ?? '' }}</a>
    </div>
</div>
