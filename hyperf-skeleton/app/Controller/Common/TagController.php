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
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use App\Service\TagService;

/**
 * @AutoController(prefix="/common/tag")
 * Class TagController
 * @package App\Controller\Common
 */
class TagController extends AbstractController
{
    /**
     * @Inject
     * @var TagService
     */
    private TagService $tagService;

    /**
     * 获取所有标签
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAll()
    {
       $list = $this->tagService->getAll();
       return $this->success($list);
    }

    /**
     * 获取热门标签
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getHotTags() 
    {
        $list = $this->tagService->getHotTags();
        return $this->success($list);
    }
}