<?php
/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  泽湾普罗信息技术有限公司(ZYProSoft)
 * @license  GPL
 */

declare(strict_types=1);

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
    private CommentService $commentService;

    /**
     * 获取所有的回复
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
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

    /**
     * 快速回复某一条评论
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function reply(AppAdminRequest $request)
    {
        $this->validate([
            'content' => 'string|required|min:1|max:500',
            'commentId' => 'integer|min:1|exists:comment,comment_id',
        ]);
        $content = $request->param('content');
        $commentId = $request->param('commentId');
        $comment = $this->commentService->reply($commentId, $content);
        return $this->success($comment);
    }

    /**
     * 删除某条评论
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
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