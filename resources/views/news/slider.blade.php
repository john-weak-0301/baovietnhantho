@if(!blank($sliders))
    <section class="md-section section-new-featued">
        <div class="container">

            <div class="swiper-new-featured">
                <div class="swiper__module swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($sliders as $slider)
                            <div class="new-featured">
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-6 ">
                                        <div class="new-featured__media">
                                            @if($slider->image_slider)
                                                <img src="{{ $slider->image_slider }}" alt="{{ esc_attr($slider->title) }}" />
                                            @else
                                                <img src="{!! $slider->getImageUrl('large') ?? '' !!}" alt="{{ esc_attr($slider->title) }}" />
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6 col-lg-6 ">
                                        <div class="new-featured__content">
                                            <span class="new-featured__cat">
                                                {!! $slider->categories ? ($slider->categories->first()->name ?? '') : '' !!}
                                            </span>

                                            <h2 class="new-featured__title">
                                                <a href="{{ $slider->url }}" title="{{ $slider->title }}">
                                                    {{ Str::words($slider->title, 14) }}
                                                </a>
                                            </h2>

                                            <p class="new-featured__text">{!! $slider->excerpt ?? '' !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-pagination-custom"></div>

                    <div class="swiper-button-custom">
                        <div class="swiper-button-prev-custom"><i class="fa fa-angle-left"></i>
                        </div>
                        <div class="swiper-button-next-custom"><i class="fa fa-angle-right"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endif

@if(!blank($features))
    <section class="md-section pt-0 pb-0">
        <div class="container">
            <div class="new-min-wrapper">
                <div class="row">
                    @foreach($features as $feature)
                        <div class="col-sm-6 col-md-6 col-lg-4 ">

                            <div class="new-list">
                                <div class="new-list__media">
                                    <img src="{{ $feature->getImageUrl('medium') }}" alt="{{ esc_attr($feature->title) }}" />
                                </div>

                                <div class="new-list__body">
                                    <span class="new-list__cat">
                                        {!! $slider->categories ? ($slider->categories->first()->name ?? '') : '' !!}
                                    </span>

                                    <h2 class="new-list__title">
                                        <a href="{{ $feature->url }}" title="{{ $feature->title }}">
                                            {{ Str::words($feature->title, 14) }}
                                        </a>
                                    </h2>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
