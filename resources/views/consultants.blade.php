@extends('layouts.main')

@section('title', 'Chọn chuyên viên tư vấn')

@section('content')
    <section class="md-section section-tool-page tool-page-center">
        <div class="tool-page__content">
            <div class="container">

                {{-- Don't remove this line --}}
                <div id="counselors"></div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @if (config('captcha.sitekey'))
        <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
    @endif

    <script>
      window._recaptchaSiteKey = '{{ config('captcha.sitekey') }}';
      window._counselorsStaticData = @json([
            'provinces' => $provinces,
        ]);
    </script>

    <script src="{{ mix('js/counselors.js') }}"></script>
@endpush
