<?php
declare(strict_types=1);

return [
    'cgw' => [
        'force_auth' => env('FORCE_AUTH', false),//强制校验签名,开启后CGW协议必须带签名参数访问
        'config_list' => [
            "test" => "abcdefg",
        ]
    ],
    'rate_limit' => [
        'access_rate_limit' => 10, //每分钟访问限制次数
        'white_list' => [
            '/weixin'
        ],
    ],
    'cors' => [
        'enable_cross_origin' => env('ENABLE_CROSS_ORIGIN', true),
        'allow_cross_origins' => [
            'http://127.0.0.1:8010',
            'http://localhost',
            'http://lulinggushi.com'
        ],
    ],
];
