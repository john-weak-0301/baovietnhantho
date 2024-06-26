@component($typeForm, get_defined_vars())
    @php
        if (isset($value) && is_array($value)) {
            $checked = $value;
        } else {
            $checked = $value ? $value->pluck('id')->all() : [];
        }

        $currentId = 0;
        if ($product = request()->route('productId')) {
            $currentId = $product->id;
        }

        $products = App\Model\Product::where('id', '!=', $currentId)->with('category')->get();

        $productsByCategory = $products->mapToGroups(function($item) {
            return [
                optional($item->category)->name => $item
            ];
        });
    @endphp

    <div style="max-height: 250px; overflow: auto">
        @foreach ($productsByCategory as $cate => $products)
            <h4>{{ $cate ?: 'Chưa phân loại' }}</h4>

            <ul class="list-unstyled pl-3">
                @foreach ($products as $product)
                    <li>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" {!! checked(in_array($product->id, $checked)) !!} class="custom-control-input" id="{{ $id.'-'.$product->id }}" name="{{ $name }}[]" value="{{ $product->id }}">
                            <label class="custom-control-label" for="{{ $id.'-'.$product->id }}">{{ $product->title }}</label>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>
@endcomponent
