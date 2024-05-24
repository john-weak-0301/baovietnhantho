<div class="input-group" data-actions='@json($actions)' style="width: 180px;">
    <select name="action" class="custom-select" data-target="table.action">
        <option value="">---</option>

        @foreach($actions as $action)
            <option value="{{ $action->uriKey() }}">{{ $action->name() }}</option>
        @endforeach
    </select>

    <div class="input-group-append">
        <button class="btn btn-default" type="submit" data-action="click->table#runAction">
            <span class="sr-only">{{ __('Apply') }}</span>
            {{ get_feather_icon('play') }}
        </button>
    </div>
</div>
