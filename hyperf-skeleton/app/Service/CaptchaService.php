<?php


namespace App\Service;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Carbon\Carbon;
use ZYProSoft\Service\AbstractService;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchaService extends AbstractService
{
    const CAPTCHA_CACHE_PREFIX = 'cpt';

    const CAPTCHA_TTL = 600;

    const CAPTCHA_SAVE_DIR = '/captcha';

    protected function saveDir()
    {
        return $this->root().DIRECTORY_SEPARATOR.self::CAPTCHA_SAVE_DIR.DIRECTORY_SEPARATOR;
    }

    protected function root()
    {
        return config('server.setting.document_root');
    }

    protected function savePath($cacheKey)
    {
        return $this->saveDir().$cacheKey;
    }

    public function get()
    {
        $builder = new CaptchaBuilder();
        $builder->build();
        $phrase = $builder->getPhrase();
        $time = Carbon::now()->millisecond;
        $cacheKey = self::CAPTCHA_CACHE_PREFIX.$time;
        if ($this->fileLocal()->has($this->saveDir()) == false) {
           $isSuccess = $this->fileLocal()->createDir($this->saveDir());
           if (!$isSuccess) {
               throw new BusinessException(ErrorCode::SYSTEM_ERROR_CAPTCHA_DIR_CREATE_FAIL);
           }
        }
        $savePath = $this->savePath($cacheKey);
        $builder->save($savePath);
        $this->cache->set($cacheKey, $phrase, self::CAPTCHA_TTL);
        return [
            'path' => $savePath,
            'key' => $cacheKey,
        ];
    }

    public function validate(string $cacheKey, string $input)
    {
        $phrase = $this->cache->get($cacheKey);
        if (is_null($phrase)) {
            $savePath = $this->savePath($cacheKey);
            if ($this->fileLocal()->has($savePath)) {
                $this->fileLocal()->delete($savePath);
            }
            throw new BusinessException(ErrorCode::SYSTEM_ERROR_CAPTCHA_EXPIRED);
        }

        if ($phrase != $input) {
            throw new BusinessException(ErrorCode::SYSTEM_ERROR_CAPTCHA_INVALIDATE);
        }

        $savePath = $this->savePath($cacheKey);
        $this->fileLocal()->delete($savePath);

        return $this->success();
    }
}