<?php


namespace App\Service;
use App\Job\RefreshArticleJob;
use App\Model\Comment;
use ZYProSoft\Log\Log;

class CommentService extends BaseService
{
    public function create(int $articleId, string $content, int $parentCommentId = null)
    {
        $comment = new Comment([
            'article_id' => $articleId,
            'content' => $content,
        ]);
        if (isset($parentCommentId)) {
            $comment->parent_comment_id = $parentCommentId;
        }
        Log::info("will save comment:".$comment->toJson());
        $comment->saveOrFail();
        $this->jobDispatcher->push(new RefreshArticleJob($articleId));
    }
}