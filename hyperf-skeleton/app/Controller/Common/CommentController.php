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
    private CommentService $commentService;

    /**
     * 获取评论详情
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model
     */
    public function detail()
    {
        $this->validate([
            'commentId' => 'integer|min:1|required|exists:comment,comment_id'
        ]);
        $commentId = $this->request->param('commentId');
        return $this->commentService->detail($commentId);
    }

    /**
     * 添加评论
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create()
    {
        //先校验验证码是否正确
        $this->validateCaptcha();
        $this->validate([
            'content' => 'string|required|min:1|max:500',
            'articleId' => 'integer|required|min:1|exists:article,article_id',
            'commentId' => 'integer|min:1|exists:comment,comment_id',
            'nickname' => 'string|required|min:1|max:30',
            'email' => 'string|required|min:1|max:30',
            'avatar' => 'string|min:1|max:500',
            'site' => 'string|min:1|max:120',
        ]);
        $content = $this->request->param('content');
        $articleId = $this->request->param('articleId');
        $commentId = $this->request->param('commentId');
        $nickname = $this->request->param('nickname');
        $email = $this->request->param('email');
        $avatar = $this->request->param('avatar');
        $site = $this->request->param('site');
        $comment = $this->commentService->create($articleId, $content, $nickname, $email, $avatar, $site, $commentId);
        return $this->success($comment);
    }

    /**
     * 用户主动删除评论
     */
    public function userDelete()
    {
        $this->validate([
            'commentId' => 'integer|min:1|required|exists:comment,comment_id'
        ]);
        $commentId = $this->request->param('commentId');
        return $this->commentService->userDelete($commentId);
    }

    /**
     * 获取文章的评论列表
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list()
    {
        $this->validate([
            'articleId' => 'integer|required|min:1|exists:article,article_id',
            'pageIndex' => 'required|integer|min:0',
            'pageSize' => 'integer|min:1|max:20',
        ]);
        $articleId = $this->request->param('articleId');
        $pageIndex = $this->request->param('pageIndex');
        $pageSize = $this->request->param('pageSize');
        $list = $this->commentService->listWithArticleId($pageIndex, $pageSize, $articleId);
        return $this->success($list);
    }

    /**
     * 回复别人的评论
     * @param AuthedRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function reply(AuthedRequest $request)
    {
        $this->validate([
            'commentId' => 'integer|min:1|required|exists:comment,comment_id',
            'content' => 'string|max:500|required'
        ]);
        $commentId = $request->param('commentId');
        $content = $request->param('content');
        $this->commentService->reply($commentId, $content);
        return $this->success();
    }

    
}