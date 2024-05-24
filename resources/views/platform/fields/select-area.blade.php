@component($typeForm, get_defined_vars())
    <div data-controller="field--select-area" class="d-flex">
        <div style="width: 200px; margin-right: 7px;">
            <select
                name="{{ $name }}[province]"
                data-value="{{ $value['province'] ?? '' }}"
                data-target="field--select-area.province"
                {!! Core\Support\Util::buildHtmlAttributes($attributes->all()) !!}>
                @if (!empty($value['province']))
                    <option value="{{ sprintf('%02s', $value['province']) }}" selected>{{ rescue(function () use ($value) { return App\Model\Province::getByCode($value['province'])->getName(); }) }}</option>
                @endif
            </select>
        </div>

        <div style="width: 200px;">
            <select
                name="{{ $name }}[district]"
                data-value="{{ $value['district'] ?? '' }}"
                data-target="field--select-area.district"
                {!! Core\Support\Util::buildHtmlAttributes($attributes->all()) !!}>
                @if (!empty($value['district']))
                    <option value="{{ sprintf('%03s', $value['district']) }}" selected>{{ rescue(function () use ($value) { return App\Model\District::getByCode($value['district'])->getNameWithType(); }) }}</option>
                @endif
            </select>
        </div>
    </div>
@endcomponent
