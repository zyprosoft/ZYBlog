<?php


namespace App\Controller\Admin;
use App\Http\AppAdminRequest;
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use App\Service\CommentService;

/**
 * @AutoController(prefix="/admin/comment")
 * Class CommentController
 * @package App\Controller\Admin
 */
class CommentController extends AbstractController
{
    /**
     * @Inject
     * @var CommentService
     */
    private $commentService;

    public function list(AppAdminRequest $request)
    {
        $this->validate([
            'pageIndex' => 'required|integer|min:0',
            'pageSize' => 'required|integer|min:1|max:30',
        ]);
        $pageIndex = $request->param('pageIndex');
        $pageSize = $request->param('pageSize');
        $list = $this->commentService->list($pageIndex, $pageSize);
        return $this->success($list);
    }

    public function reply()
    {
        $this->validate([
            'content' => 'string|required|min:1|max:500',
            'commentId' => 'integer|min:1',
        ]);
        $content = $this->request->param('content');
        $commentId = $this->request->param('commentId');
        $comment = $this->commentService->reply($commentId, $content);
        return $this->success($comment);
    }

    public function delete(AppAdminRequest $request)
    {
        $this->validate([
            'commentId' => 'required|integer|min:1',
        ]);
        $commentId = $request->param('commentId');
        $this->commentService->delete($commentId);
        return $this->success();
    }
}