<?php
declare(strict_types=1);

namespace App\Controller\Common;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use ZYProSoft\Controller\AbstractController;
use App\Service\CommentService;
use ZYProSoft\Http\AuthedRequest;

/**
 * @AutoController(prefix="/common/comment")
 * Class CommentController
 * @package App\Controller\Common
 */
class CommentController extends AbstractController
{
    /**
     * @Inject
     * @var CommentService
     */
    private $commentService;

    public function detail()
    {
        $this->validate([
            'commentId' => 'integer|required'
        ]);
        $commentId = $this->request->param('commentId');
        return $this->commentService->detail($commentId);
    }

    public function create(AuthedRequest $request)
    {
        $this->validate([
            'content' => 'string|required|min:1|max:500',
            'articleId' => 'integer|required|min:1',
            'commentId' => 'integer|min:1'
        ]);
        $content = $request->param('content');
        $articleId = $request->param('articleId');
        $commentId = $request->param('commentId');
        $this->commentService->create($articleId, $content, $commentId);
        return $this->success();
    }

    public function userDelete()
    {
        $this->validate([
            'commentId' => 'integer|required'
        ]);
        $commentId = $this->request->param('commentId');
        return $this->commentService->userDelete($commentId);
    }

    public function list()
    {
        $this->validate([
            'articleId' => 'integer|required|min:1',
            'pageIndex' => 'required|integer|min:0',
            'pageSize' => 'integer|min:1|max:20',
        ]);
        $articleId = $this->request->param('articleId');
        $pageIndex = $this->request->param('pageIndex');
        $pageSize = $this->request->param('pageSize');
        $list = $this->commentService->listWithArticleId($articleId, $pageIndex, $pageSize);
        return $this->success($list);
    }

    public function reply(AuthedRequest $request)
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