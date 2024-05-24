<div class="table-component"
     data-controller="table"
     data-table-action-url="{{ $table->getActionHandleUrl() }}">
    <header class="row mb-3">
        <div class="col-sm-8 col-lg-9">
            @includeWhen(!blank($actions = $table->getActions()), 'core::layouts.table.actions', compact('actions'))

            {!! $filters ?? '' !!}
        </div>

        <div class="col-md-4 col-lg-3">
            @includeWhen($table->showSearchInput, 'core::layouts.table.search')
        </div>
    </header>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-outline table-vcenter card-table">
                <thead>
                    <tr>
                        @foreach($columns as $th)
                            <th width="{{$th->width}}" class="{{ $table->getColumnClasses($th) }}">
                                <div>
                                    @if($th->sortable)
                                        <a href="?sort={{revert_sort($th->column)}}"
                                           class="@if(!is_sort($th->column)) text-muted @endif">
                                            {{$th->name}}

                                            @if(is_sort($th->column))
                                                @if(get_sort($th->column) === 'asc')
                                                    <i class="icon-sort-amount-asc"></i>
                                                @else
                                                    <i class="icon-sort-amount-desc"></i>
                                                @endif
                                            @endif
                                        </a>
                                    @else
                                        @if($th instanceof Core\Elements\Table\ID)
                                            <input type="checkbox"/>
                                        @else
                                            {{ $th->name }}
                                        @endif
                                    @endif

                                    @isset($th->filter)
                                        @includeIf("platform::partials.filters.{$th->filter}", [
                                            'th' => $th
                                        ])
                                    @endisset
                                </div>

                                @if($filter = get_filter_string($th->column))
                                    <div data-controller="screen--filter">
                                        <a href="#"
                                           data-action="screen--filter#clearFilter"
                                           data-filter="{{$th->column}}"
                                           class="badge badge-pill badge-light">
                                            {{ $filter }}
                                        </a>
                                    </div>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    {!! $table->bodyRows() !!}
                </tbody>
            </table>
        </div>
    </div>

    @if($table->shoudPagination())
        @include('core::layouts.table.pagination')
    @endif
</div>
