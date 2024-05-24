<ul class="submenu">
    <li>
        <div class="row">
            <div class="col-lg-3 ">

                <!-- textbox-02 -->
                <div class="textbox-02">
                    <div class="textbox-02__title">{{ __('Giới thiệu') }}</div>
                    <p class="textbox-02__text">{{ __('Hiện tại, Bảo Việt Nhân thọ đang cung cấp cho khách hàng 50 sản phẩm các loại nhằm đáp ứng tốt nhất nhu cầu bảo vệ, đầu tư tài chính của người dân Việt Nam.') }}</p>
                </div><!-- End / textbox-02 -->

            </div>
            <div class="col-lg-3 ">

                <!-- list-menu -->
                <div class="list-menu">
                    <div class="list-menu__title">{{ __('Bảo Việt Nhân thọ') }}</div>
                    <ul class="list-menu__list">
                        <li><a href="/bao-viet-nhan-tho">{{ __('Lịch sử phát triển') }}</a></li>
                        <li><a href="/tam-nhin-va-su-menh">{{ __('Tầm nhìn và sứ mệnh') }}</a></li>
                        <li><a href="/ban-lanh-dao">{{ __('Ban lãnh đạo') }}</a></li>
                        <li><a href="/bao-cao-tai-chinh">{{ __('Báo cáo tài chính') }}</a></li>
                        <li><a href="/tin-tuc/danh-muc/hoat-dong-cong-dong">{{ __('Hoạt động cộng đồng') }}</a></li>
                    </ul>
                </div><!-- End / list-menu -->

            </div>

            <div class="col-lg-3 ">

                <!-- textbox-02 -->
                <div class="textbox-02">
                    <div class="textbox-02__title"><a href="{{ route('contact') }}">{{ __('Liên hệ') }}</a></div>
                    <p class="textbox-02__text">{{ __('Liên hệ ngay với chúng tôi để được hỗ trợ dịch vụ tốt nhất, chúng tôi sẵn sàng hỗ trợ mọi nhu cầu và câu hỏi liên quan đến bảo hiểm') }}</p>
                </div><!-- End / textbox-02 -->

            </div>

            <div class="col-lg-3 ">
                @include('partials.chon-chuyen-vien')
            </div>
        </div>
    </li>
</ul>
