<?php


namespace App\Service;
use App\Job\RefreshArticleJob;
use App\Model\Comment;
use ZYProSoft\Facade\Auth;
use ZYProSoft\Log\Log;

class CommentService extends BaseService
{
    public function create(int $articleId, string $content, int $parentCommentId = null)
    {
        $comment = new Comment();
        $comment->user_id = Auth::userId();
        $comment->article_id = $articleId;
        $comment->content = $content;
        if (isset($parentCommentId)) {
            $comment->parent_comment_id = $parentCommentId;
        }
        Log::info("will save comment:".$comment->toJson());
        $comment->saveOrFail();
        $this->jobDispatcher->push(new RefreshArticleJob($articleId));
    }

    public function list(int $pageIndex, int $pageSize, int $articleId)
    {
        return Comment::query()->where('article_id',$articleId)->with(['author','parentComment'])
                               ->offset($pageIndex * $pageSize)
                               ->limit($pageSize)->get();
    }
}