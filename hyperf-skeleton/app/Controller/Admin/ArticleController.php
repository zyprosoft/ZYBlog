<?php


namespace App\Controller\Admin;
use Hyperf\HttpServer\Annotation\AutoController;
use ZYProSoft\Controller\AbstractController;
use App\Http\AdminArticleRequest;
use Hyperf\Di\Annotation\Inject;
use App\Service\ArticleService;

/**
 * @AutoController(prefix="/admin/article")
 * Class ArticleController
 * @package App\Controller\Admin
 */
class ArticleController extends AbstractController
{
    /**
     * @Inject
     * @var ArticleService
     */
    private $articleService;

    public function create(AdminArticleRequest $request)
    {
        $params = $request->validated();
        $title = $params['title'];
        $content = $params['content'];
        $tags = $params['tags'];
        $categoryId = $params['categoryId'];
        $this->articleService->createArticle($title, $content, $tags, $categoryId);
        return $this->success();
    }
}