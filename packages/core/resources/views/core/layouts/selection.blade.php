<div data-controller="screen--filter">
    <ul class="nav nav-pills align-items-center mr-3">
        <li class="nav-item">
            <span>{{ __('Lọc theo:') }}</span>
        </li>

        @foreach($filters as $idx => $filter)
            @if(!$filter->display)
                @continue
            @endif

            <li class="nav-item dropdown">
                <a class="btn btn-link dropdown-item dropdown-toggle" href="#" data-toggle="dropdown" data-filter-index="{{$idx}}">
                    {{ $filter->name() }}
                </a>

                <div class="dropdown-menu" data-action="click->screen--filter#onMenuClick" data-target="screen--filter.filterItem" style="min-width: 320px;">
                    <div class="px-3 py-2">
                        {!! $filter->render() !!}

                        <div class="dropdown-divider"></div>

                        <div class="text-center">
                            <button
                                type="submit"
                                id="button-filter"
                                form="filters"
                                class="btn btn-primary">
                                {{ __('Áp dụng bộ lọc') }}
                            </button>
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>

    @foreach($filters as $filter)
        @if($filter->display && $filter->isApply())
            <a href="{{ $filter->resetLink() }}" class="badge badge-light">
                {{ $filter->value() }}
            </a>
        @endif
    @endforeach
</div>
