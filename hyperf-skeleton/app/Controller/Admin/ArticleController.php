<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Http\AppAdminRequest;
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

    public function update(AdminArticleRequest $request)
    {
        $params = $request->validated();
        $articleId = $params['articleId'];
        $title = $params['title'];
        $content = $params['content'];
        $tags = $params['tags'];
        $categoryId = $params['categoryId'];
        $this->articleService->updateArticle($articleId, $title, $content, $tags, $categoryId);
        return $this->success();
    }

    public function list()
    {
        $this->validate([
            'pageIndex' => 'required|integer|min:0',
            'pageSize' => 'required|integer|min:1|max:30',
            'categoryId' => 'integer|min:1',
        ]);
        $pageIndex = $this->request->param('pageIndex');
        $pageSize = $this->request->param('pageSize');
        $categoryId = null;
        if ($this->request->hasParam('categoryId')) {
            $categoryId = $this->request->param('categoryId');
        }
        $list = $this->articleService->getArticleList($pageIndex, $pageSize, $categoryId);
        return $this->success($list);
    }

    public function detail()
    {
        $this->validate([
            'articleId' => 'integer|exists:article,article_id|required',
        ]);
        $articleId = $this->request->param('articleId');
        $article = $this->articleService->getArticleDetail($articleId);
        return $this->success($article);
    }

    public function moveToTrash(AppAdminRequest $request)
    {
        $this->validate([
            'articleId' => 'required|integer|min:1',
        ]);
        $articleId = $request->param('articleId');
        $this->articleService->moveToTrash($articleId);
        return $this->success();
    }
}
