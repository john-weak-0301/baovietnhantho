<div class="row">
    <div class="col-sm-5">
        <small class="text-muted inline m-t-sm m-b-sm">
            {{ __("Displayed records: :from-:to of :total",[
                'from' => ($items->currentPage()-1)*$items->perPage()+1,
                'to' => ($items->currentPage()-1)*$items->perPage()+count($items->items()),
                'total' => $items->total(),
            ]) }}
        </small>
    </div>

    <div class="col-sm-7 text-right text-center-xs">
        {!! $items->appends(request()->except(['page', '_token']))->links('platform::partials.pagination') !!}
    </div>
</div>
