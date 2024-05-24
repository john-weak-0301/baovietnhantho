<table class="table table-bordered">
    <tr>
        <td></td>
        <td>{{ __('Giờ mở cửa') }}</td>
        <td>{{ __('Giờ đóng cửa') }}</td>
    </tr>
    @foreach($dates as $value => $text)
        <tr>
            <td>{{ $text }}</td>
            <td><input class="form-control" type="time" name="branch[working_days][{{$value}}][0]"
                       value="{{ $branch->working_days[$value]['0'] ?? '08:00' }}"></td>
            <td><input  class="form-control" type="time" name="branch[working_days][{{$value}}][1]"
                       value="{{ $branch->working_days[$value]['1'] ?? '17:00'}}"></td>
        </tr>
    @endforeach
</table>

