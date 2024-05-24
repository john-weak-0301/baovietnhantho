<section
    class="page-title {{ (isset($style) && $style === 'style_2') ? 'page-title__style-02' : '' }}"
    style="background-image: url('{{ $image ?? '' }}');"
>
    @if (isset($overlay) && $overlay)
        <div class="md-overlay"></div>
    @endif

    <div class="page-title__wrapper">
        <div class="container">
            <h1 class="page-title__title">{{ $title ?? '' }}</h1>

            @if (Breadcrumbs::exists())
                {{ Breadcrumbs::view('breadcrumbs') }}
            @endif
        </div>
    </div>
</section>
