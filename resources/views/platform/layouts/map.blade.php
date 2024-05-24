@component($typeForm,get_defined_vars())
<div data-controller="fields--map"
     data-fields--map-id="{{$id}}"
     data-fields--map-zoom="{{$zoom}}"
>
    <div id="{{$id}}" class="osmap-map b m-b w-full" style="min-height: {{ $attributes['height'] }}">

    </div>
    <div class="row mt-3">
        <div class="col-md">
            <label for="{{$name}}[lat]">{{ __('Vĩ độ') }}</label>
            <input class="form-control"
                   id="marker__latitude"
                   data-target="fields--map.lat"
                   @if($required ?? false) required @endif
                   name="{{$name}}[lat]"
                   value="{{ $value['lat'] ?? '21.0294498' }}"/>
        </div>
        <div class="col-md">
            <label for="{{$name}}[lng]">{{ __('Kinh độ') }}</label>
            <input class="form-control"
                   id="marker__longitude"

                   data-target="fields--map.lng"
                   @if($required ?? false) required @endif
                   name="{{$name}}[lng]"
                   value="{{ $value['lng'] ?? '105.8544441' }}"/>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md">
            <label>{{ __('Tìm kiếm địa điểm') }}</label>
            <input class="form-control" type="text"
                   value="{{$valuename ?? ''}}"
                   data-target="fields--map.search"
                   data-action="keyup->fields--map#search"/>
            <div id="marker__results"></div>
        </div>
    </div>
</div>
@endcomponent
