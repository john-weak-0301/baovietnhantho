<div class="widget">
    <div class="widget-banner">
        @if ($content = setting('global_banner'))
            {!! wpautop(do_shortcode(wp_kses_post($content))) !!}
        @endif
    </div>
</div>
