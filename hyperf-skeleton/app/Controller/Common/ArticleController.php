<?php


namespace App\Controller\Common;
use App\Service\ArticleService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use ZYProSoft\Controller\AbstractController;
use App\Service\CommentService;

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

    /**
     * @Inject
     * @var CommentService
     */
    private $commentService;

    public function list()
    {
        $pageIndex = $this->request->param('pageIndex', 0);
        $pageSize = $this->request->param('pageSize', 20);
        $categoryId = null;
        if ($this->request->has('categoryId')) {
            $categoryId = $this->request->param('categoryId');
        }
        $articleList = $this->articleService->getArticleList($pageIndex, $pageSize, $categoryId);
        return $this->success($articleList);
    }

    public function addComment()
    {
        $this->validate([
            'articleId' => 'integer|exists:article,article_id|required',
            'content' => 'string|max:500|required',
            'parentCommentId' => 'integer',
        ]);
        $articleId = $this->request->param('articleId');
        $parentCommentId = $this->request->param('parentCommentId');
        $content = $this->request->param('content');
        $this->commentService->create($articleId, $content, $parentCommentId);
        return $this->success();
    }
}