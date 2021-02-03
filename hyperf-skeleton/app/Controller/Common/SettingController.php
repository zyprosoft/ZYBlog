<?php


namespace App\Controller\Common;
use App\Http\AppAdminRequest;
use App\Service\CommonService;
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;

/**
 * @AutoController(prefix="/common/setting")
 * Class SettingController
 * @package App\Controller\Common
 */
class SettingController extends AbstractController
{
    /**
     * @Inject
     * @var CommonService
     */
    private $service;

    public function clearCache(AppAdminRequest $request)
    {
        return $this->service->clearSystemCache();
    }
}