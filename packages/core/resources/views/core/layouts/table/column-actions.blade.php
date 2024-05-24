<div class="dropdown">
    <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true">
        <i class="icon-options-vertical"></i>
        <span class="sr-only">{{ __('Hành động') }}</span>
    </a>

    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
        @foreach($actions as $action)
            {!! optional($action)->build($data) !!}
        @endforeach
    </div>
</div>
