<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Available menu
    |--------------------------------------------------------------------------
    */

    'menu' => [
        'main'          => 'Main',
        'main_mobile'   => 'Main (Mobile)',
        'sidebar-about' => 'Giới thiệu',
        'footer-1'      => 'Footer 1 (Mục tiêu của bạn)',
        'footer-2'      => 'Footer 2 (Dịch vụ khách hàng)',
        'footer-3'      => 'Footer 3 (Giới thiệu)',
        'footer-4'      => 'Footer 4 (Tuyển dụng)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Compare attributes for products.
    |--------------------------------------------------------------------------
    */

    'compare_attributes' => [
        'vi-sao'           => 'Vì sao bạn nên quan tâm sản phẩm này',
        'nhom-san-pham'    => 'Nhóm sản phẩm',
        'ql-bao-ve'        => 'Quyền lợi Bảo Vệ',
        'ql-dt-tiet-kiem'  => 'Quyền lợi Đầu Tư tiết kiệm ',
        'ql-linh-hoat'     => 'Quyền lợi Linh hoạt',
        'ql-gia-dinh'      => 'Quyền lợi Gia đình',
        'sp-bo-tro'        => 'Sản phẩm bổ trợ',
        'do-tuoi-tham-gia' => 'Độ tuổi tham gia',
        'thoi-han-hd'      => 'Thời hạn hợp đồng',
        'thoi-han-dp'      => 'Thời hạn đóng phí',
        'phi-dong'         => 'Phí đóng tối thiểu',
    ],

    /*
    |--------------------------------------------------------------------------
    | Danh sach cac ke hoach
    |--------------------------------------------------------------------------
    */

    'objectives' => [
        'y-te' => [
            'name'    => 'Bảo vệ và chăm sóc y tế',
            'related' => ['an-gia-thinh-vuong', 'an-phat-bao-gia'],
        ],

        'tai-chinh' => [
            'name'    => 'Đảm bảo an toàn tài chính',
            'related' => ['tron-doi-yeu-thuong', 'bao-ve-va-cham-soc-y-te', 'an-binh-thinh-vuong'],
        ],

        'giao-duc' => [
            'name'    => 'Tương lai cho con trẻ',
            'related' => ['an-tam-hoc-van'],
        ],

        'dau-tu' => [
            'name'    => 'Tiết kiệm và đầu tư',
            'related' => ['an-hung-phat-loc', 'an-phat-cat-tuong'],
        ],

        'huu-tri' => [
            'name'    => 'Nghỉ hưu an nhàn',
            'related' => ['huu-tri-an-khang'],
        ],
    ],

    'target_amounts' => [
        'default' => [
            '600000000',
            '500000000',
            '400000000',
            '300000000',
            '200000000',
            '100000000',
        ],

        'huu-tri' => [
            '5000000',
            '6000000',
            '7000000',
            '8000000',
            '9000000',
            '10000000',
            '12000000',
            '15000000',
            '20000000',
        ],
    ],

];
