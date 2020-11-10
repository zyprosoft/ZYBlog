<?php


namespace App\Controller\Common;
use App\Service\ArticleService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use ZYProSoft\Controller\AbstractController;
use App\Service\CommentService;
use ZYProSoft\Http\AuthedRequest;

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

    /**
     * 必须具有登录态
     * @param AuthedRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function addComment(AuthedRequest $request)
    {
        $this->validate([
            'articleId' => 'integer|exists:article,article_id|required',
            'content' => 'string|max:500|required',
            'parentCommentId' => 'integer',
        ]);
        $articleId = $request->param('articleId');
        $parentCommentId = $request->param('parentCommentId');
        $content = $request->param('content');
        $this->commentService->create($articleId, $content, $parentCommentId);
        return $this->success();
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

    public function commentList()
    {
        $this->validate([
            'pageIndex' => 'integer',
            'pageSize' => 'integer|max:20',
            'articleId' => 'integer|exists:article,article_id|required'
        ]);
        $pageIndex = $this->request->param('pageIndex', 0);
        $pageSize = $this->request->param('pageSize', 20);
        $articleId = $this->request->param('articleId');
        $comments = $this->commentService->list($pageIndex, $pageSize, $articleId);
        return $this->success($comments);
    }

    /**
     * 回复某一个评论
     * @param AuthedRequest $request
     */
    public function replyComment(AuthedRequest $request)
    {
        $this->validate([
            'commentId' => 'integer|required',
            'content' => 'string|max:500|required'
        ]);
        $commentId = $request->param('commentId');
        $content = $request->param('content');
        $this->commentService->reply($commentId, $content);
        return $this->success();
    }
}