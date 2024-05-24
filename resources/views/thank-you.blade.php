@extends('layouts.main')

@section('title', 'Thank you!')

@section('meta')
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="refresh" content="15;url={{ route('home') }}" />
@endsection

@push('styles')
    <style>
        .md-section--thankyou {
            min-height: calc(100vh - 125px);
        }

        .md-section--thankyou h2 {
            font-size: 32px;
            line-height: 1.4;
            letter-spacing: 1px;
            margin-bottom: 25px;
        }

        .md-section--thankyou p {
            color: #2a79c0;
            font-size: 18px;
            letter-spacing: 1px;
        }

        .md-section--thankyou a {
            color: #dda307;
            font-size: 22px;
            font-weight: 700;
        }

        .md-section--thankyou a > i {
            font-size: 28px;
            line-height: 1;
            display: inline-block;
            margin-right: 3px;
        }

        .md-section--thankyou a > span {
            color: #aaa;
            font-weight: 400;
            display: inline-block;
            margin-left: 10px;
        }

        @media (min-width: 1024px) {
            .md-section--thankyou {
                background-image: url("/img/thank-you-pt01.png"), url("/img/thank-you-pt02.png");
                background-size: initial;
                background-repeat: no-repeat, no-repeat;
                background-position: center right, left bottom;
            }

            .md-section--thankyou h2 {
                font-size: 42px;
                letter-spacing: 2px;
            }
        }

        @media (min-width: 1200px) {
            .md-section--thankyou {
                padding-top: 150px;
                padding-bottom: 150px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="md-section md-section--thankyou">
        <div class="container">
            <p><img src="/img/thank-you-phone.png" alt="thank-you"></p>
            <h2>{!! $messages ?? 'Cảm ơn bạn đã liên hệ <br> Bảo Việt Nhân Thọ đặt lịch tư vấn' !!}</h2>
            <p>Chúng tôi sẽ hồi đáp bạn trong thời gian sớm nhất.</p>

            <a href="{{ route('home') }}">
                <i class="fa fa-angle-left"></i> Quay về trang chủ
                <span id="thankyou-countdown">(10)</span>
            </a>
        </div>
    </div>

    <script>
      (function() {
        var tick = 10;
        var countdown = document.getElementById('thankyou-countdown');
        var interval = setInterval(function() {
          tick -= 1;
          countdown.innerText = '(' + tick + ')';

          if (tick === 0) {
            clearInterval(interval);
            document.location.href = '{{ route('home') }}';
          }
        }, 1000);
      })();
    </script>
@endsection
