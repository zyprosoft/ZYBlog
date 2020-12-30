<?php


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
}