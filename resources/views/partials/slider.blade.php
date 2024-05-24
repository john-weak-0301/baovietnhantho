<div class="hero">
    <div class="hero__slide swiper-container">
        <div class="swiper-wrapper">
            @foreach($sliders as $slider)
                <div class="swiper-slide">
                    <div class="hero__slide-bg" style="background-image: url({!! $slider['background'] ?? '' !!});"></div>
                    <div class="hero__slide-bgmobile" style="background-image: url({!! $slider['background_mobile'] ?? $slider['background'] ?? '' !!});"></div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="hero__wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 ">
                    <div class="hero__thumb swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($sliders as $slider)
                                <div class="swiper-slide">
                                    <div class="hero__title">
                                        <a href="{!! $slider['link'] ?? '#' !!}">{!! $slider['title'] ?? '' !!}</a>
                                    </div>

                                    <p class="hero__text">{!! $slider['description'] ?? '' !!}</p>
                                    <a class="btn btn-secondary" href="{!! $slider['link'] ?? '#' !!}">{!! $slider['text_link'] ?? '' !!}</a>
                                </div>
                            @endforeach
                        </div>

                        <div class="swiper-pagination-custom"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('home.search')
</div><!-- End / hero -->
