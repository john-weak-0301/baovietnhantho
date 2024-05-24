<div class="footer">
    <div class="container">
        <div class="footer__header">
            <div class="row">
                <div class="col-md-3 col-lg-3 col-xs-offset-0 col-sm-offset-0 col-md-offset-2 col-lg-offset-2 ">
                    <div class="footer__backtotop">
                        {{ __('Lên đầu trang') }}
                        <img src="/img/svg/icon-backtotop.svg" alt="{{ __('Lên đầu trang') }}" />
                    </div>
                </div>

                <div class="col-md-7 col-lg-7 ">
                    <form class="form-search form-search__light footer__search" method="GET" action="/tim-kiem">
                        <input class="form-control" type="text" name="q" placeholder="{{ __('Nhập thông tin cần tìm kiếm') }}" />
                        <span class="form-icon"><img src="{{ asset('img/svg/icon-search.svg') }}" alt="{{ __('Tìm kiếm') }}" /></span>
                    </form>
                </div>
            </div>
        </div>

        <div class="footer__content">
            <div class="footer__contentfix">
                <div class="footer__item">
                    <div class="footer__itemtitle">Liên hệ với chúng tôi</div>

                    <div class="footer__info first">
                        <span class="footer__infoicon"><i class="fa fa-phone"></i></span>

                        <div class="footer__infotitle">
                            <a href="tel:*1166">*1166</a>

                            <i style="display: block; font-size: 12px; font-weight: 400;">hoặc</i>

                            <a href="tel:{{ str_replace( ' ', '', config('baoviet.hotline')) }}">
                                {{ config('baoviet.hotline') }}
                                <small>{{ __('– nhánh số 4') }}</small>
                            </a>
                        </div>
                    </div>

                    <div class="footer-infowrap">
                        <a class="footer__info" href="{{ route('branches') }}">
                            <span class="footer__infoicon"><i class="fa fa-map-marker"></i></span>
                            {{__('Chi nhánh')}}
                        </a>

                        <a class="footer__info" href="mailto:{{ config('baoviet.email') }}">
                            <span class="footer__infoicon"><i class="fa fa-envelope"></i></span>
                            {{ __('Gửi email') }}
                        </a>

                        <a class="footer__info" href="#">
                            <span class="footer__infoicon"><i class="fa fa-comments"></i></span>
                            {{ __('Chat với tư vấn') }}
                        </a>

                        <a class="footer__info" href="{{config('baoviet.facebook')}}">
                            <span class="footer__infoicon"><i class="fa fa-facebook"></i></span>
                            Facebook
                        </a>
                    </div>
                </div>

                <div class="footer__item footer-item-js">
                    <div class="footer__itemtitle">{{ __('Mục tiêu của bạn') }}</div>
                    {!! $footerMenu1->asUl(['class' => 'footer__list']) !!}
                </div>

                <div class="footer__item footer-item-js">
                    <div class="footer__itemtitle">{{ __('Dịch vụ khách hàng') }}</div>
                    {!! $footerMenu2->asUl(['class' => 'footer__list']) !!}
                </div>

                <div class="footer__item footer-item-js">
                    <div class="footer__itemtitle">{{ __('Giới thiệu') }}</div>
                    {!! $footerMenu3->asUl(['class' => 'footer__list']) !!}
                </div>

                <div class="footer__item footer-item-js">
                    <div class="footer__itemtitle">{{ __('Tuyển dụng') }}</div>
                    {!! $footerMenu4->asUl(['class' => 'footer__list']) !!}
                </div>
            </div>
        </div>

        <div class="footer__footer">
            <div class="row">
                <div class="col-sm-9 col-md-9 col-lg-9 ">
                    <div class="footer__text">
                        @if ($copyright = setting('footer_copyright'))
                            {!! wpautop($copyright) !!}
                        @else
                            <p>Tổng Công ty Bảo Việt Nhân thọ * Trụ sở chính: Tầng 37, Keangnam Ha Noi Landmark Tower, Đường Phạm Hùng, Quận Nam Từ Liêm, Hà Nội</p>
                            <p>Tổng đài Chăm sóc Khách hàng:
                                <strong>*1166</strong> hoặc 1900 558899 nhánh số 4</p>
                            <p>Ban Quản trị Website: Phòng Marketing, Điện thoại: (+84 24) 6251 7777, Fax: (+84 24) 3577 0958</p>
                            <p>Ghi rõ nguồn "<a href="{{ url('/') }}">https://baovietnhantho.com.vn</a>" khi sử dụng thông tin từ website này
                            </p>
                        @endif
                    </div>

                    <p class="footer__copyright">
                        Copyright &copy; {{ date('Y') }} Tập đoàn Bảo Việt.
                    </p>
                </div>

                <div class="col-sm-3 col-md-3 col-lg-2 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1">
                    <div class="footer__logo">
                        <a href="{{ url('/') }}"><img src="{{ asset('img/logo.png?rel=footer') }}" alt="Bảo Việt Nhân Thọ" /></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="footer-xs-toolbar">
    <div class="footer-fixed-fixheight2"></div>

    <div class="footer-fixed footer-fixed-style2">
        @yield('footer-toolbar')

        <div class="footer-fixed__wrap">
            <a href="/lap-ke-hoach"><span><img src="/img/svg/icon-category2.svg" alt="Mục tiêu của bạn" />Mục tiêu<br> của bạn</span></a>
            <a href="/tu-van"><span><img src="/img/svg/icon-chonnhanvien.svg" alt="Đăng ký tư vấn" />Đăng ký<br> tư vấn</span></a>
            <a href="tel:1900558899"><span><img src="/img/svg/icon-phone2.svg" alt="Liên hệ" />Liên hệ <br> ngay</span></a>
        </div>
    </div>
</div>
