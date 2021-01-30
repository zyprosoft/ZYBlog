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
    'mail' => [
        'smtp' => [
            'host' => 'smtp.qq.com',
            'auth' => true,
            'username' => '1003081775@qq.com',
            'password' => 'kavphdirftxubcig',
            'port' => '465', //qq邮箱经测试是465端口+ssl协议有效果
            'secure' => 'ssl'
        ]
    ],
    'upload' => [
        'system_type' => env('UPLOAD_TYPE', 'local'), //使用上传的类型，对应下面的配置的key，如本地使用local,七牛云使用qiniu
        'local' => [
            'image_dir' => env('LOCAL_IMAGE_DIR', '/image'),//本地图片路径，位于server.settings.document_root配置目录之下
            'url_prefix' => env('LOCAL_IMAGE_URL_PREFIX',''),//当上传到本地的时候，拼接的图片路径
        ],
        'qiniu' => [
            'token_ttl' => env('QINIU_TOKEN_TTL', 3600),
        ],
    ]
];