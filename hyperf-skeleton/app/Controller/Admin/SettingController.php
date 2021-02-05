<?php


namespace App\Controller\Admin;
use App\Http\AppAdminRequest;
use App\Service\CommonService;
use App\Service\UserService;
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;

/**
 * @AutoController(prefix="/admin/setting")
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

    /**
     * @Inject
     * @var UserService
     */
    private $userService;

    public function clearCache(AppAdminRequest $request)
    {
        $this->service->clearSystemCache();
        return $this->success();
    }

    public function updatePassword(AppAdminRequest $request)
    {
        $this->validate([
            'password' => 'string|required|min:1'
        ]);
        $password = $request->param('password');
        $this->userService->updatePassword($password);
        return $this->success();
    }
}