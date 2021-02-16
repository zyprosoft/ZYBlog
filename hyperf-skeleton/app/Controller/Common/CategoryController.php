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
use App\Service\CategoryService;

/**
 * @AutoController(prefix="/common/category")
 * Class CategoryController
 * @package App\Controller\Common
 */
class CategoryController extends AbstractController
{
    /**
     * @Inject
     * @var CategoryService
     */
    private CategoryService $service;

    /**
     * 获取所有分类
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAll()
    {
        return $this->success($this->service->getAll());
    }

    /**
     * 获取所有归档月份
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAllArchiveDate()
    {
        return $this->success($this->service->getAllArchiveDate());
    }
}