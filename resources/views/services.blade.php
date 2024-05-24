@extends('layouts.main')

@section('content')
    <section class="md-section bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-3 ">
                    <div class="title md-text-center">
                        <h1 class="title__title">{{ __('Dịch vụ khách hàng') }}</h1>
                        <p class="title__text">{{ __('Chúng tôi sẵn sàng hỗ trợ mọi nhu cầu và câu hỏi liên quan đến bảo hiểm') }}</p>
                    </div>

                    <form class="form-search form-search__light" action="/tim-kiem" method="GET">
                        <input type="hidden" name="type" value="services">
                        <input id="autocomplete-services" class="form-control" type="text" name="q" placeholder="{{ __('Nhập thông tin cần tìm kiếm') }}"/>
                        <span class="form-icon"><i class="fa fa-search"></i></span>
                    </form>
                </div>
            </div>

            <div class="mt-40"></div>
            <div class="row row-eq-height">
                <div class="col-sm-6 col-md-6 col-lg-4 ">
                    <div class="contact-infobox">
                        <div class="contact-infobox__inner">
                            <img src="{{ asset('img/svg/contact-icon.svg') }}" alt="Phản ánh">
                            <p class="contact-infobox__title">{{ __('Phản ánh') }}</p>

                            <p class="contact-info__text">
                                {!! __('Liên hệ với chúng tôi qua điện thoại để <br> có thể đặt câu hỏi và giải quyết thắc <br> mắc của bạn') !!}
                            </p>

                            <a class="btn btn-light" href="/lien-he">{{ __('Gửi phản ánh') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-4 ">
                    <div class="contact-infobox contact-infobox--map">
                        <div class="contact-infobox__inner">
                            <img src="{{ asset('img/svg/contact-icon2.svg') }}" alt="Tìm chi nhánh">
                            <p class="contact-infobox__title">{{ __('Tìm chi nhánh') }}</p>

                            <p class="contact-info__text">
                                {!! __('Liên hệ với chúng tôi qua điện thoại để <br> có thể đặt câu hỏi và giải quyết thắc <br> mắc của bạn') !!}
                            </p>

                            <a class="btn btn-light" href="/chi-nhanh">{{ __('Tìm kiếm chi nhánh') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-4 ">
                    <div class="contact-infobox contact-infobox--mail">
                        <div class="contact-infobox__inner">
                            <img src="{{ asset('img/svg/contact-icon3.svg') }}" alt="Thanh toán phí bảo hiểm">
                            <p class="contact-infobox__title">{{ __('Thanh toán phí bảo hiểm') }}</p>

                            <p class="contact-infobox__text">{!! __('Thanh toán phí bảo hiểm dễ dàng <br> và thuận tiện hơn với My BVLIFE') !!}</p>
                            <a class="btn btn-light" rel="noreferrer" href="https://mybvlife.baovietnhantho.com.vn/#/pages/premium-payment?result=0" target="_blank">{{ __('Thanh toán') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            @foreach($categories as $category)
                <div class="panelbox" id="{{ $category->slug }}">
                    <h2 class="panelbox__title">{{ $category->name }}</h2>

                    <div class="panelbox__body">
                        <div class="row">
                            <div class="col-lg-5 ">
                                <p class="panelbox__desc">{{ $category->description }}</p>
                            </div>
                        </div>

                        <ul class="panelbox__list">
                            @foreach($category->service as $service)
                                <li>
                                    <h3 style="margin: 0;">
                                        <a href="{{ route('service', $service->slug) }}">
                                            {{ $service->title }}<i class="fa fa-angle-right"></i>
                                        </a>
                                    </h3>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
