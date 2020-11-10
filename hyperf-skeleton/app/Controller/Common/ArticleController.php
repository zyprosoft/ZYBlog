<?php


namespace App\Controller\Common;
use App\Service\ArticleService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use ZYProSoft\Controller\AbstractController;

/**
 * @AutoController(prefix="/common/article")
 * Class ArticleController
 * @package App\Controller\Common
 */
class ArticleController extends AbstractController
{
    /**
     * @Inject
     * @var ArticleService
     */
    private $articleService;

    public function list()
    {
        $pageIndex = $this->request->param('pageIndex', 0);
        $pageSize = $this->request->param('pageSize', 20);
        $categoryId = null;
        if ($this->request->has('categoryId')) {
            $categoryId = $this->request->param('categoryId');
        }
        return $this->articleService->getArticleList($pageIndex, $pageSize, $categoryId);
    }
}