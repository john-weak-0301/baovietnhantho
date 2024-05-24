<form class="select-plan text-center" method="GET" action="/lap-ke-hoach">
    <div class="select-plan__title h2" style="margin-bottom: 0">Lập kế hoạch tài chính</div>

    <div class="select-plan__title">
        <select class="select-plan__select select2Js" name="plan">
            <option value="y-te" data-img="/img/svg/iconbox3.svg">{{ __('Bảo vệ và chăm sóc y tế') }}</option>
            <option value="tai-chinh" data-img="/img/svg/iconbox2.svg">{{ __('Đảm bảo an toàn tài chính') }}</option>
            <option value="giao-duc" data-img="/img/svg/iconbox1.svg">{{ __('Tương lai cho con trẻ') }}</option>
            <option value="dau-tu" data-img="/img/svg/iconbox5.svg">{{ __('Tiết kiệm và đầu tư') }}</option>
            <option value="huu-tri" data-img="/img/svg/iconbox6.svg">{{ __('Nghỉ hưu an nhàn') }}</option>
        </select>
    </div>

    <button class="btn btn-secondary btn-lg btn-shadow" type="submit">Lập kế hoạch</button>
    @csrf
</form>
