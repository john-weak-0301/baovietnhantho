<div class="form-group">
    <label>{{ __('Thời gian tư vấn') }}
    </label>
    <table class="table table-bordered">
        <tr>
            <td></td>
            <td>{{ __('Sáng') }}</td>
            <td>{{ __('Chiều') }}</td>
            <td>{{ __('Tối') }}</td>
        </tr>
        @foreach($dates as $value => $text)
            <tr>
                <td>{{ $text }}</td>
                <td style="text-align: center"><input type="checkbox" name="consultant[data][{{$value}}][morning]"
                                                      value="1" {!! isset($consultant->data[$value]['morning']) ? 'checked' : '' !!} >
                </td>
                <td style="text-align: center"><input type="checkbox" name="consultant[data][{{$value}}][afternoon]"
                                                      value="1" {!! isset($consultant->data[$value]['afternoon']) ? 'checked' : '' !!}>
                </td>
                <td style="text-align: center"><input type="checkbox" name="consultant[data][{{$value}}][night]"
                                                      value="1" {!! isset($consultant->data[$value]['night']) ? 'checked' : '' !!} >
                </td>
            </tr>
        @endforeach
    </table>
</div>
