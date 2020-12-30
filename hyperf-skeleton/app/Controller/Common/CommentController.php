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
}