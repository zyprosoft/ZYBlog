<?php


namespace App\Service;
use App\Job\RefreshArticleJob;
use App\Model\Comment;

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
        $comment->saveOrFail();
        $this->jobDispatcher->push(new RefreshArticleJob($articleId));
    }
}