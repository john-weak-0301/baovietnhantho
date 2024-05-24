<div class="row" data-async>
    <div class="hbox hbox-auto-xs no-gutters">
        <div class="hbox-col wi-col">
            <div class="vbox">
                <div class="row-row">
                    <div class="wrapper">
                        @isset($form_title)<h2 class="h4 font-thin mb-3">{{ $form_title }}</h2>@endisset
                        {!! $form ?? '' !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="hbox-col">
            <div class="vbox">
                <div class="wrapper">
                    {!! $table ?? '' !!}
                </div>
            </div>
        </div>
    </div>
</div>
