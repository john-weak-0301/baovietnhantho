<form class="hero-search" method="GET" action="/lap-ke-hoach">
    <div><span class="hero-search__title">{{ __('Lập kế hoạch tài chính:') }}</span></div>

    <div>
        <select class="form-control" name="plan">
            <option value="y-te" data-img="/img/svg/iconbox3.svg">{{ __('Bảo vệ và chăm sóc y tế') }}</option>
            <option value="tai-chinh" data-img="/img/svg/iconbox2.svg">{{ __('Đảm bảo an toàn tài chính') }}</option>
            <option value="giao-duc" data-img="/img/svg/iconbox1.svg">{{ __('Tương lai cho con trẻ') }}</option>
            <option value="dau-tu" data-img="/img/svg/iconbox5.svg">{{ __('Tiết kiệm và đầu tư') }}</option>
            <option value="huu-tri" data-img="/img/svg/iconbox6.svg">{{ __('Nghỉ hưu an nhàn') }}</option>
        </select>
    </div>

    <div>
        <button class="btn btn-secondary" type="submit">{{ 'Xem ngay' }}</button>
    </div>

    @csrf
</form>
