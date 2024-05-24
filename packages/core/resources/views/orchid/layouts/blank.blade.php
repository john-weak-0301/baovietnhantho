@foreach($manyForms as $key => $column)
    @php $column = Arr::wrap($column) @endphp

    @foreach($column as $item)
        {!! $item instanceof Illuminate\Contracts\Support\Renderable ? $item->render() : $item !!}
    @endforeach
@endforeach
