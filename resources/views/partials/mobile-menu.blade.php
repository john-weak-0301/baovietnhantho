<div class="menu-mobile">
    <div class="menu-mobile__inner">
        <nav class="menu-mobile__nav">
            <ul class="menu-list">
                <li class="menu-item-current menu-item-has-children">
                    <a href="/bao-ve-va-cham-soc-y-te">Mục tiêu của bạn</a>
                    <span class="menu-item-icon"><i class="fa fa-angle-down"></i></span>

                    <ul class="submenu">
                        <li><a href="/bao-ve-va-cham-soc-y-te">Bảo vệ và chăm sóc y tế</a></li>
                        <li><a href="/tuong-lai-cho-con-tre">Tương lai cho con trẻ</a></li>
                        <li><a href="/tiet-kiem-va-dau-tu">Tiết kiệm và đầu tư</a></li>
                        <li><a href="/dam-bao-an-toan-tai-chinh">Đảm bảo an toàn tài chính</a></li>
                        <li><a href="/nghi-huu-an-nhan">Nghỉ hưu an nhàn</a></li>
                    </ul>
                </li>

                <li class="menu-item-has-children">
                    <a href="/san-pham">Sản phẩm</a>
                    <span class="menu-item-icon"><i class="fa fa-angle-down"></i></span>

                    <ul class="submenu">
                        <li><a href="/san-pham">Tất cả</a></li>
                        <li><a href="/san-pham/danh-muc/bao-ve">Bảo vệ</a></li>
                        <li><a href="/san-pham/danh-muc/tich-luy">Tích lũy</a></li>
                        <li><a href="/san-pham/danh-muc/dau-tu">Đầu tư</a></li>
                        <li><a href="/san-pham/danh-muc/doanh-nghiep">Doanh nghiệp</a></li>
                        <li><a href="/san-pham/danh-muc/huu-tri">Hưu trí</a></li>
                    </ul>
                </li>

                <li class="menu-item-has-children">
                    <a href="{{ route('services') }}">Dịch vụ khách hàng</a>
                    <span class="menu-item-icon"><i class="fa fa-angle-down"></i></span>

                    <ul class="submenu">
                        @foreach ($serviceCategories as $category)
                            <li><a href="/dich-vu-khach-hang#{{ $category->slug }}">{{ $category->name ?? '' }}</a></li>
                        @endforeach
                    </ul>
                </li>

                <li><a href="/goc-chuyen-gia">Góc chuyên gia</a></li>

                <li class="menu-item-has-children">
                    <a href="/bao-viet-nhan-tho">Bảo Việt Nhân Thọ</a>
                    <span class="menu-item-icon"><i class="fa fa-angle-down"></i></span>

                    {!! App\Utils\MenuFactory::make('sidebar-about')->asUl(['class' => 'submenu']) !!}
                </li>

                <li><a href="/tin-tuc">Tin tức</a></li>
            </ul>
        </nav>

        <div class="menu-mobile__info">
            <a class="info-phone" href="tel:*1166">
                <span>
                    <img src="/img/svg/header-phone.png" alt="phone" />
                    <span style="display: block">*1166 <span style="font-size: 12px;font-weight: 400;font-style: italic;">hoặc</span></span>

                    <span style="display: block;">
                        1900 558899
                        <small>– nhánh số 4 </small>
                    </span>
                </span>
            </a>

            <a href="/chi-nhanh">
                <span><img src="/img/svg/header-marker.svg" alt="Chi nhánh" />Chi nhánh</span>
            </a>
        </div>
    </div>

    <a class="menu-mobile__user" href="{{ config('baoviet.mybv_life') }}" rel="noreferrer">
        <img src="/img/svg/header-user.svg" alt="MyBVLife" />MyBVLife
    </a>
</div><!-- /.menu-mobile -->
