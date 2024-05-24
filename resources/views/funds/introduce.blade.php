@extends('layouts.main')

@section('content')
    <section class="page-title" style="background-image: url('http://baovietnhantho.awe7.com/storage/8dd0c51c-f6ea-48e1-8fc7-ddbf3a60db85/bg-home.jpg');">
        <div class="page-title__wrapper">
            <div class="container">
                <h1 class="page-title__title">Thông tin Quỹ liên kết đơn vị</h1>
                <ul class="page-title__breadcrumb">
                    <li><a href="{{ config('app.url') }}">Trang Chủ</a></li>
                    <li><a href="{{ route('services') }}">Dịch vụ khách hàng</a></li>
                    <li><span>Chi tiết giá đơn vị quỹ</span></li>
                </ul>
            </div>
        </div>
    </section>

    <div class="md-section"><div class="container">
        <div class="layout layout-no-sidebar">
            <div class="layout-content">
                <div class="layout-content__entry">
                    <div class="layout-content__detail">
                        <h2 class="text-center">GIỚI THIỆU QUỸ LIÊN KẾT ĐƠN VỊ</h2>
                        <table class="table-phamviapdung">
                            <thead>
                                <tr>
                                    <th class="first-row"></th>
                                    @foreach($funds as $fund)
                                        <th class="child-row text-center">{{ $fund->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="{{ count($funds) + 1 }}" class="group-cols"><strong>Mục tiêu</strong></td>
                                </tr>
                                <tr>
                                    <td class="first-row"></td>
                                    @foreach($funds as $fund)
                                        <td class="child-row">{{ $fund->desc_target }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td colspan="{{ count($funds) + 1 }}" class="group-cols"><strong>Lợi nhận kỳ vọng và rủi ro đầu tư</strong></td>
                                </tr>
                                <tr>
                                    <td class="first-row"></td>
                                    @foreach($funds as $fund)
                                        <td class="child-row">
                                            <div>{{ $fund->desc_profit }}</div>
                                            <h4>Rủi ro đầu tư: {{ $fund->risks_of_investing }}</h4>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td colspan="{{ count($funds) + 1 }}" class="group-cols"><strong>Danh mục đầu tư (% tài sản)</strong></td>
                                </tr>
                                <tr>
                                    <td class="first-row"></td>
                                    @foreach($funds as $fund)
                                        <td class="child-row">{!! $fund->desc_invest !!}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                        <div class="md-skin-dark has-background" style="background:linear-gradient(147.28deg, rgba(0, 149, 218, 0.9),rgba(0, 92, 171, 0.9)), url( http://baovietnhantho.awe7.com/storage/8dd0c51c-f6ea-48e1-8fc7-ddbf3a60db85/bg-home.jpg );background-size:cover" id="lap-ke-hoach">
                            <div class="display-flex-solution md-skin-dark">
                                <div class="statistic-block">
                                    <h4 class="solution-icon__title">Ngày: {{ now()->format('d/m/Y') }}</h4>
                                    <ul class="solution-icon__list">
                                        @foreach($funds as $fund)
                                            <li class="solution-icon__icon fund-info">
                                                <div>
                                                    <div class="fund-name_1">{{ $fund->name }}</div>
                                                    <div class="fund-cost_1">{{ getNewFundCost($fund) }}đ</div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="next-period solution-icon__title">Ngày định giá tiếp theo: {{ (new DateTime())->modify('next tuesday')->format('d/m/Y') }}. Ngày định giá là ngày thứ 3 hàng tuần</div>
                                </div>
                                <div class="text-label_1 h4 text-center" style="margin-bottom: 0; padding-top: 50px;">GIÁ ĐƠN VỊ QUỸ HIỆN TẠI</div>
                            </div>
                        </div>
                        <div id="funds">
                            <fund-statistic :funds='@json($funds)' :fund_costs='@json($fundCosts)'/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div></div>
@endsection

@push('links')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@endpush

@push('styles')
    <style type="text/css">
        .group-cols {
            background: #e0e0e0;
            font-weight: bold;
        }
        .first-row {
            width: 10% !important;
        }
        .child-row {
            width: calc(90%/{{count($funds)}}) !important;
        }
        td.child-row {
            color: #8e8e8e;
        }
        th.child-row {
            text-transform: unset !important;
        }
        .table-phamviapdung {
            margin-top: 30px;
        }
        .text-label_1 {
            font-size: 30px;
        }
        .statistic-block {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 80px;
        }
        .solution-icon__title {
            text-transform: unset !important;
        }
        .solution-icon__list {
            width: 60%;
            margin: auto;
        }
        .fund-name_1 {
            text-transform: capitalize;
        }
        .fund-info {
            width: calc(100%/{{count($funds)}}) !important;
        }
        .next-period {
            margin-top: 40px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ mix('js/fund.js') }}"></script>
@endpush
