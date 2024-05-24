<div class="header__header">
    <div class="header__logo">
        @if (request()->is('/'))
            <h1 class="h1-logo">
                @include('partials.logo')
            </h1>
        @else
            @include('partials.logo')
        @endif
    </div>

    <div class="header__fixgrow"></div>

    <ul class="header__info">
        <li class="info-has-children">
            <div class="header__item">
                <a href="https://tuyendung.baovietnhantho.com.vn/" rel="noreferrer">
                    <img src="{{ asset('img/svg/bullhorn.svg') }}" alt="bullhorn" />
                    {{ __('Tuyển dụng') }}
                </a>
            </div>

            <div class="info-submenu info-submenu--dropdown">
                <a href="https://tuyendung.baovietnhantho.com.vn/">
                    {{ __('Tuyển dụng cán bộ') }}
                </a>

                <a href="/tuyen-dung-dai-ly">
                    {{ __('Tuyển dụng Tư vấn tài chính') }}
                </a>
            </div>
        </li>

        <li class="info-has-children">
            <div class="header__item">
                <a href="tel:*1166">
                    <img src="{{ asset('img/svg/header-phone.png') }}" alt="phone" />
                    *1166
                </a>
            </div>

            <div class="info-submenu">
                <a href="tel:{{ str_replace( ' ', '', config('baoviet.hotline')) }}">
                    <span>{{ config('baoviet.hotline')}}</span>
                    {{ __('Nhánh 4') }}
                </a>

                <a href="tel:{{ str_replace( ' ', '', config('baoviet.hotline2')) }}">
                    <span>{{ config('baoviet.hotline2') }}</span>
                    * {{ __('Miễn phí với số điện thoại đã đăng ký trên hợp đồng Bảo Việt Nhân thọ') }}
                </a>
            </div>
        </li>

        <li>
            <div class="header__item">
                <a href="{{ url('chi-nhanh') }}">
                    <img src="{{ asset('img/svg/header-marker.svg') }}" alt="Chi nhánh" />
                    {{ __('Chi nhánh') }}
                </a>
            </div>
        </li>

        <li>
            <div class="header__item header-icon-search">
                <a href="#">
                    <img src="{{ asset('img/svg/header-search.svg') }}" alt="Tìm kiếm" />
                    {{ __('Tìm kiếm') }}
                </a>
            </div>
        </li>
    </ul>
</div>

<div class="header__inner">
    <div class="header__desktop">

        <a class="header__iconHome" href="{{ url('/') }}">
            <img src="{{ asset('img/svg/header-home.svg') }}" alt="{{ __('Trang chủ') }}" />
        </a>

        <div class="header__nav" id="header-nav">
            {!! $headerMenu->asUl(['class' => 'menu-list'], ['class'=>'submenu']) !!}
        </div>

        <div class="header__fixgrow"></div>

        <div class="header__user">
            <a href="{{ config('baoviet.mybv_life')}}" rel="noreferrer">
                <img src="{{ asset('img/svg/header-user.svg') }}" alt="MyBVLife" />
                MyBVLife
            </a>
        </div>

        <div class="header__iconmenu" id="nav-icon2" style="display: none;">
            <span></span><span></span><span></span><span></span><span></span><span></span>
        </div>
    </div>

    <div class="header__mobile">
        <div class="header__mobileitem">
            <div class="header__iconmenu" id="nav-icon2m">
                <span></span><span></span><span></span><span></span><span></span><span></span>
            </div>
        </div>

        <div class="header__mobileitem">
            <div class="header__logo">
                <a class="logo-dark" href="/">
                    <img src="{{ asset('img/logo.png?rel=header') }}" alt="logo" />
                </a>
            </div>
        </div>

        <div class="header__mobileitem">
            <div class="header-icon-search"><i class="fa fa-search"></i></div>
        </div>
    </div>
</div>
