<section class="md-section pt-0 pb-0 z-index-initial">
    <div class="menubox-min-section-wrap">
        <div class="menubox-min-fix"></div>

        <div class="menubox-min-section">
            <div class="container">
                <div class="menubox-min menubox-min-uppercase">
                    <div class="nav-wrap-scroll">
                        {!! Menu::get('productCategories')->asUl(['class' => 'menubox-min__list']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
