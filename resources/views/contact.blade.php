@extends('layouts.main')

@section('title', 'Liên hệ')

@section('content')
    @include('partials.page-title', [
        'title' => __('Liên hệ'),
        'style' => 'style_2',
        'image' => '/img/image/bg-lienhe.jpg',
    ])

    <section class="md-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">
                    <div class="title md-text-center">
                        <p class="title__title">Liên hệ ngay với chúng tôi để được hỗ trợ dịch vụ tốt nhất</p>
                        <p class="title__text">Chúng tôi sẵn sàng hỗ trợ mọi nhu cầu và câu hỏi liên quan đến bảo hiểm</p>
                    </div>
                </div>
            </div>

            <div class="row row-eq-height">
                <div class="col-sm-6 col-md-6 col-lg-4 ">
                    <div class="contact-infobox">
                        <div class="contact-infobox__inner">
                            <img src="/img/svg/contact-icon.svg" alt="Liên hệ">
                            <p class="contact-infobox__title">Liên hệ</p>
                            <p class="contact-infobox__text">
                                Tổng đài chăm sóc khách hàng:<br>
                                <strong>*1166</strong> hoặc <strong>1900 558899</strong> nhánh số 4. <br>
                                Tổng đài miễn phí cho khách hàng hiện tại: <strong>1800 6966</strong>
                            </p>
                            <a class="btn btn-light" href="tel:*1166">Liên hệ ngay</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-4 ">
                    <div class="contact-infobox contact-infobox--mail">
                        <div class="contact-infobox__inner">
                            <img src="/img/svg/contact-icon4.svg" alt="email">
                            <p class="contact-infobox__title">Gửi email cho chúng tôi</p>
                            <p class="contact-infobox__text">Các câu hỏi/vấn đề quan tâm của Quý<br>khách sẽ được phản hồi trong 24h làm việc.</p>
                            <a class="btn btn-light" href="mailto:{{ config('baoviet.email') }}">Gửi email ngay</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-6 col-lg-4 ">
                    <div class="contact-infobox contact-infobox--map">
                        <div class="contact-infobox__inner">
                            <img src="/img/svg/contact-icon2.svg" alt="Tìm chi nhánh">
                            <p class="contact-infobox__title">Tìm chi nhánh</p>
                            <p class="contact-infobox__text">Tìm trung tâm hoặc chi nhanh Bảo Việt<br>gần bạn nhất</p>
                            <a class="btn btn-light" href="/chi-nhanh">Tìm kiếm chi nhánh</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="md-section pt-0 pb-0">
        <div class="map" id="map-page">
            <div class="map__marker">
                <img src="/img/svg/icon-info-location.svg" alt="Location" />
                <p>
                    <span class="marker__title">Trụ sở chính</span>Tầng 37, Keangnam Ha Noi Landmark Tower, Đường Phạm
                    Hùng, Quận Nam Từ Liêm, Hà Nội
                    <span class="marker-info"><a href="#">(+84) 24 3994 5542</a><a href="#">Chỉ đường</a></span>
                </p>
            </div>
        </div>
    </section>

    <section class="md-section pt-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1 ">
                    <div class="contact-form">
                        <div class="contact-form__header">
                            <div class="contact-form__title h3">{{ __('Để chúng tôi có thể hỗ trợ bạn, vui lòng cho biết các thông tin sau đây') }}</div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form class="contact-form__inner" method="post" action="{{ route('contact.submit') }}">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-6 ">
                                    <div class="form-item">
                                        <input class="form-control" required type="text" name="name" value="{{ old('name') }}" placeholder="{{ __('Họ tên của bạn') }}" />

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-6 ">
                                    <div class="form-item">
                                        <input class="form-control" required type="text" value="{{ old('phone_number') }}" name="phone_number" placeholder="{{ __('Số điện thoại') }}" />

                                        @if ($errors->has('phone_number'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('phone_number') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-6 ">
                                    <div class="form-item">
                                        <input class="form-control" type="text" name="email" placeholder="Email" value="{{ old('email') }}" />

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 col-lg-6 ">
                                    <div class="form-item">
                                        <select class="form-control" name="province_code" value="{{ old('province_code') }}">
                                            <option value="">{{ __('Tỉnh, thành phố') }}</option>
                                            @foreach($province as $code => $text)
                                                <option value="{{ $code }}" {{ $code == old('province_code') ? 'selected' : '' }}>{{ $text->name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('province'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('province') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-12 ">
                                    <div class="form-item">
                                        <input class="form-control" name="address" value="{{ old('address') }}" placeholder="{{ __('Địa chỉ') }}">

                                        @if ($errors->has('address'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-item">
                                        <textarea class="form-control" required name="message" placeholder="{{ __('Lời nhắn của bạn') }}">{{ old('message') }}</textarea>
                                        @if ($errors->has('message'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('message') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: center; margin-bottom: 15px;">
                                @if (config('captcha.sitekey'))
                                    {!! NoCaptcha::display() !!}
                                @endif

                                @if ($errors->has('g-recaptcha-response'))
                                    <p class="help-block">{{ $errors->first('g-recaptcha-response') }}</p>
                                @endif
                            </div>

                            <button class="btn btn-secondary" type="submit">{{ __('Gửi lời nhắn') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @if (config('captcha.sitekey'))
        {!! NoCaptcha::renderJs() !!}
    @endif

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD2ukfeVAiyOTsmvrUIXx2LLz7sVuqWOZo" async defer></script>
@endpush
