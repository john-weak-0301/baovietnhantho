<form id="contact-form" class="{{ isset($style) && $style === 'light' ? 'form-item--light' : '' }}" method="post" action="{{ route('contact.submit') }}">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="form-item ">
                <input class="form-control" type="text" name="name" required placeholder="{{ __('Họ tên của bạn') }}" />

                @if ($errors->has('name'))
                    <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
                @endif
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6">
            <div class=" form-item">
                <input class="form-control" type="text" name="phone_number" required placeholder="{{ __('Số điện thoại') }}" />

                @if ($errors->has('phone_number'))
                    <span class="help-block"><strong>{{ $errors->first('phone_number') }}</strong></span>
                @endif
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="form-item">
                <input class="form-control" type="email" name="email" placeholder="Email" />

                @if ($errors->has('email'))
                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                @endif
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6">
            <div class="form-item">
                <select class="form-control" name="province_code">
                    <option value="">{{ __('Tỉnh, thành phố') }}</option>

                    @foreach($province as $value => $text)
                        <option value="{{ $value }}">{{ $text->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('province'))
                    <span class="help-block"><strong>{{ $errors->first('province') }}</strong></span>
                @endif
            </div>
        </div>

        <div class="col-lg-12 ">
            <div class="form-item">
                <input class="form-control" name="address" placeholder="{{ __('Địa chỉ liên hệ') }}">

                @if ($errors->has('address'))
                    <span class="help-block"><strong>{{ $errors->first('address') }}</strong></span>
                @endif
            </div>

            <div class="form-item">
                <textarea class="form-control" name="message" required placeholder="{{ __('Lời nhắn của bạn') }}"></textarea>

                @if ($errors->has('message'))
                    <span class="help-block"><strong>{{ $errors->first('message') }}</strong></span>
                @endif
            </div>

        </div>
    </div>

    <div style="display: flex; justify-content: center; margin-bottom: 15px;">
        @if (config('captcha.sitekey'))
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
        @endif

        @if ($errors->has('g-recaptcha-response'))
            <p class="help-block">{{ $errors->first('g-recaptcha-response') }}</p>
        @endif
    </div>

    <button class="btn btn-secondary" type="submit">{{ __('Gửi lời nhắn') }}</button>
</form>
