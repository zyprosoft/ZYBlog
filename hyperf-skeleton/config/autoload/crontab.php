<?php
use Hyperf\Crontab\Crontab;
use ZYProSoft\Task\ClearExpireCaptchaTask;

return [
    // 是否开启定时任务
    'enable' => true,
    'crontab' => [
        (new Crontab())->setName('clearCaptcha')
                       ->setRule('*/10 * * * *')
                       ->setCallback([ClearExpireCaptchaTask::class, 'execute'])
                       ->setMemo('定时清除过期的验证码'),
    ]
];