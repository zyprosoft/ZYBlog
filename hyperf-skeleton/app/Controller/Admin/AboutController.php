<?php


namespace App\Controller\Admin;
use App\Http\AppAdminRequest;
use App\Service\CommonService;
use ZYProSoft\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="/admin/about")
 * Class AboutController
 * @package App\Controller\Admin
 */
class AboutController extends AbstractController
{
    /**
     * @Inject
     * @var CommonService
     */
    private $service;

    public function commitAboutInfo(AppAdminRequest $request)
    {
        $params = $this->validate([
            'email' => 'required|string|min:1',
            'nickname' => 'string|min:1',
            'blogName' => 'string|min:1',
            'birthday' => 'date',
            'wx' => 'string|min:1',
            'qq' => 'string|min:1',
            'wb' => 'string|min:1',
            'constellation' => 'string|min:1',
            'hobby' => 'string|min:1',
            'job' => 'string|min:1',
            'company' => 'string|min:1',
            'github' => 'string|min:1',
            'Facebook' => 'string|min:1',
            'Twitter' => 'string|min:1',
            'icp' => 'string|min:1',
            'music' => 'string|min:1',
            'qqCode' => 'string|min:1',
            'wxCode' => 'string|min:1',
            'workHistory' => 'string|min:1',
            'introduce' => 'string|min:1',
        ]);
        return $this->success($this->service->commitAboutInfo($params));
    }
}