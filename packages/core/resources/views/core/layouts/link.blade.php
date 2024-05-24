@if(!empty($group))
    <button class="btn btn-link dropdown-item" data-toggle="dropdown" aria-expanded="false">
        <i class="{{ $icon ?? '' }} m-r-xs"></i>{{ $name ?? '' }}
    </button>

    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow bg-white" x-placement="bottom-end">
        @foreach($group as $item)
            {!! optional($item)->build($query) !!}
        @endforeach
    </div>
@elseif($modal)
    <button
        type="button"
        class="btn btn-link dropdown-item"
        data-action="screen--base#targetModal"
        data-modal-title="{{ $title ?? '' }}"
        data-modal-key="{{ $modal ?? '' }}"
        data-modal-action="{{ $formAction ?? url()->current() }}/{{ $method }}"
    >
        <i class="{{ $icon ?? '' }} m-r-xs"></i>{{ $name ?? '' }}
    </button>
@elseif($method)
    <button
        type="submit"
        formaction="{{ $formAction ?? url()->current() }}/{{ $method }}"
        form="post-form"
        @if ($confirm)data-confirm="{{ $confirm }}" @endif
        class="btn btn-link dropdown-item">
        @isset($icon)<i class="{{ $icon }} m-r-xs"></i>@endisset
        {{ $name ?? '' }}
    </button>
@else
    <a href="{{ $link ?? '' }}" class="btn btn-link dropdown-item">
        <i class="{{ $icon ?? '' }} m-r-xs"></i>{{ $name ?? '' }}
    </a>
@endif
