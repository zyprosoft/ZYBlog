<?php
/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  泽湾普罗信息技术有限公司(ZYProSoft)
 * @license  GPL
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
    private $service;

    public function getAll()
    {
        return $this->success($this->service->getAll());
    }

    public function getAllArchiveDate()
    {
        return $this->success($this->service->getAllArchiveDate());
    }
}