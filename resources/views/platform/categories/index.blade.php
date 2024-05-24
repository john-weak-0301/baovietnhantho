<div class="row" data-async>
    <div class="hbox hbox-auto-xs no-gutters">
        @if($createForm)
            <div class="hbox-col wi-col">
                <div class="vbox">
                    <div class="row-row">
                        <div class="wrapper">
                            <h2 class="h4 font-thin mb-3">{{__('Thêm danh mục')}}</h2>
                            {!! $createForm ?? '' !!}

                            <button type="submit"
                                    formaction="{{ route('platform.categories.create', 'save') }}"
                                    form="post-form"
                                    class="btn btn-primary">
                                <span class="icon-check m-r-xs"></span><span>{{ __('Tạo') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="hbox-col">
            <div class="vbox">
                <div class="wrapper">
                    {!! $table ?? '' !!}
                </div>
            </div>
        </div>
    </div>
</div>
