<?php
declare(strict_types=1);

return [
    'zgw' => [
        'force_auth' => env('FORCE_AUTH', false),//强制校验签名,开启后ZGW协议必须带签名参数访问
        'sign_ttl' => 10,
        'config_list' => [
            "test" => "abcdefg",
        ]
    ],
    'captcha' => [
        'ttl' => 600,
        'prefix' => 'cpt',
        'dirname' => '/captcha'
    ],
    'cors' => [
        'enable_cross_origin' => env('ENABLE_CROSS_ORIGIN', true),
        'allow_cross_origins' => [
            'http://127.0.0.1:8010',
            'http://localhost',
            'http://lulinggushi.com',
            'http://dev.blog.lulinggushi.com'
        ],
    ],
    'rate_limit' => [
        'access_rate_limit' => 10, //频率限制次数
        'access_rate_ttl' => 20, //频率限制秒，两者组合为每20秒内最多允许10次请求单一接口
        'white_list' => [
            '/weixin'
        ],
    ],
    'clear_log' => [
        'days' => 3, // 只保留三天的日志，三天以前的自动清除,设置成-1表示不执行清除任务
    ],
];