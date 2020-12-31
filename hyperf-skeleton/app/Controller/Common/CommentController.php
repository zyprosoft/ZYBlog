<?php
declare(strict_types=1);

namespace App\Controller\Common;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use ZYProSoft\Controller\AbstractController;
use App\Service\CommentService;

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

    public function create()
    {
        $this->validate([
            'content' => 'string|required|min:1|max:500',
            'articleId' => 'integer|required|min:1',
            'commentId' => 'integer|min:1'
        ]);
        $content = $this->request->param('content');
        $articleId = $this->request->param('articleId');
        $commentId = $this->request->param('commentId');
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
}