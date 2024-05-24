<div class="row" data-async>
    <div class="col-sm-8 col-lg-9">
        <a class="{{ request('status') ? 'btn btn-space' : 'btn btn-primary' }}"
           href="{{ route('platform.consultants') }}">{{ __('Tất cả') }}</a>
        <a class="{{ request('status') && request('status') === 'pending' ? 'btn btn-primary' : 'btn btn-space' }}"
           href="{{ route('platform.consultants') }}?status=pending">{{ __('Chưa tư vấn') }}</a>
        <a class="{{ request('status') && request('status') === 'processed' ? 'btn btn-primary' : 'btn btn-space' }}"
           href="{{ route('platform.consultants') }}?status=processed">{{ __('Đã tư vấn') }}</a>
    </div>
    <div class="hbox hbox-auto-xs no-gutters">
        <div class="hbox-col">
            <div class="vbox">
                <div class="wrapper">
                    {!! $table ?? '' !!}
                </div>
            </div>
        </div>
    </div>
</div>


