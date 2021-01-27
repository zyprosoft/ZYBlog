<?php


namespace App\Service;
use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Self_;
use ZYProSoft\Service\AbstractService;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchaService extends AbstractService
{
    const CAPTCHA_CACHE_PREFIX = 'cpt:';
    const CAPTCHA_TTL = 600;

    public function get()
    {
        $builder = new CaptchaBuilder();
        $builder->build();
        $phrase = $builder->getPhrase();
        $time = Carbon::now()->millisecond;
        $cacheKey = self::CAPTCHA_CACHE_PREFIX.$time;
        $this->cache->set($cacheKey, $phrase, self::CAPTCHA_TTL);
        return [
            
        ];
    }
}