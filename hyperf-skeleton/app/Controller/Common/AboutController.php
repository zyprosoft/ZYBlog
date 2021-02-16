<?php
/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  ZYProSoft
 * @license  MIT
 */
declare(strict_types=1);

namespace App\Controller\Common;
use App\Component\OneSentenceComponent;
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use App\Service\CommonService;

/**
 * @AutoController(prefix="/common/about")
 * Class AboutController
 * @package App\Controller\Common
 */
class AboutController extends AbstractController
{
    /**
     * @Inject
     * @var CommonService
     */
    private CommonService $service;

    /**
     * 获取关于博主的详细信息
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function info()
    {
        return $this->success($this->service->getAboutInfo());
    }

    /**
     * 获取关于我的文章信息
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function pageInfo()
    {
        return $this->success($this->service->getAboutPageInfo());
    }

    /**
     * 获取一言的随机一句话
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getOneSentence()
    {
        $component = make(OneSentenceComponent::class);
        $result = $component->getOneSentence();
        if ($result->isSuccess()) {
            return $this->success($result->data);
        }else{
            return  $this->success();
        }
    }
}