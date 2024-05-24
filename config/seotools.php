<?php

return [
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'       => 'Bảo Việt Nhân Thọ - Công ty bảo hiểm nhân thọ hàng đầu Việt Nam',
            'description' => 'Bảo Việt Nhân Thọ là công ty phát hành hợp đồng bảo hiểm nhân thọ đầu tiên tại Việt Nam vào năm 1996. Hiện là công ty có lịch sử hoạt động lâu năm và ngày càng khẳng định vị thế dẫn đầu thị trường',
            'separator'   => ' - ',
            'keywords'    => [],
            'canonical'   => null,
            'robots'      => 'all',
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
        ],
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => false, // set false to total remove
            'description' => '', // set false to total remove
            'url'         => null, // Set null for using Url::current(), set false to total remove
            'type'        => false,
            'site_name'   => false,
            'images'      => [
                '/img/bao-viet-nhan-tho.jpg',
            ],
        ],
    ],
    'twitter'   => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            //'card'        => 'summary',
            //'site'        => '@LuizVinicius73',
        ],
    ],
];
