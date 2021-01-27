<?php


namespace App\Controller\Common;
use App\Service\CaptchaService;
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;

/**
 * @AutoController (prefix="/common/captcha")
 * Class CaptchaController
 * @package App\Controller\Common
 */
class CaptchaController extends AbstractController
{
    /**
     * @Inject
     * @var CaptchaService
     */
    private $service;

    public function get()
    {
        return $this->success($this->service->get());
    }

    public function check()
    {
        $this->validate([
            'key' => 'string|required|min:1',
            'code' => 'string|required|min:1',
        ]);
        $key = $this->request->param('key');
        $code = $this->request->param('code');
        return $this->service->validate($key, $code);
    }
}