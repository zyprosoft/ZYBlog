<?php


namespace App\Controller\Common;
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController (prefix="/common/captcha")
 * Class CaptchaController
 * @package App\Controller\Common
 */
class CaptchaController extends AbstractController
{
    public function get()
    {
        return $this->success($this->captchaService->get());
    }

    public function refresh()
    {
        $this->validate([
            'key' => 'string|required|min:1'
        ]);
        $cacheKey = $this->request->param('key');
        return $this->captchaService->refresh($cacheKey);
    }
}