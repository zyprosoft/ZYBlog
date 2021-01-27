<?php


namespace App\Service;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Carbon\Carbon;
use ZYProSoft\Log\Log;
use ZYProSoft\Service\AbstractService;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchaService extends AbstractService
{
    const CAPTCHA_CACHE_PREFIX = 'cpt';

    const CAPTCHA_TTL = 600;

    const CAPTCHA_SAVE_DIR = '/captcha';

    protected function saveDir()
    {
        return self::CAPTCHA_SAVE_DIR.DIRECTORY_SEPARATOR;
    }

    protected function subDirPath($cacheKey)
    {
        $result = $this->publicFileService->createPublicSubDirIfNotExist($this->saveDir());
        if (!$result) {
            return  null;
        }
        return $this->saveDir().$cacheKey;
    }

    protected function savePath($cacheKey)
    {
        return $this->publicFileService->publicPath($this->subDirPath($cacheKey));
    }

    public function get()
    {
        $builder = new CaptchaBuilder();
        $builder->build();
        $phrase = $builder->getPhrase();
        $time = Carbon::now()->timestamp;
        $cacheKey = self::CAPTCHA_CACHE_PREFIX.$time;
        $subDirPath = $this->subDirPath($cacheKey);
        $savePath = $this->savePath($cacheKey);
        Log::info("will save captcha path:$savePath");
        $builder->save($savePath);
        $this->cache->set($cacheKey, $phrase, self::CAPTCHA_TTL);
        return [
            'path' => $subDirPath,
            'key' => $cacheKey,
        ];
    }

    public function validate(string $cacheKey, string $input)
    {
        $phrase = $this->cache->get($cacheKey);
        if (is_null($phrase)) {
            $savePath = $this->subDirPath($cacheKey);
            $this->publicFileService->deletePublicPath($savePath);
            throw new BusinessException(ErrorCode::SYSTEM_ERROR_CAPTCHA_EXPIRED);
        }

        if ($phrase != $input) {
            throw new BusinessException(ErrorCode::SYSTEM_ERROR_CAPTCHA_INVALIDATE);
        }

        $savePath = $this->subDirPath($cacheKey);
        $this->publicFileService->deletePublicPath($savePath);

        return $this->success();
    }
}