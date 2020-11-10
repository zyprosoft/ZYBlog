<?php


namespace App\Service;
use App\Facade\ArticleServiceFacade;
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
        return Comment::query()->where('article_id',$articleId)
                               ->with(['author','parentComment'])
                               ->offset($pageIndex * $pageSize)
                               ->limit($pageSize)->get();
    }

    public function replyList(int $commentId)
    {
        return Comment::query()->where('parent_comment_id', $commentId)
                               ->with('author')
                               ->get();
    }

    public function reply(int $commentId, string $content)
    {
        $comment = Comment::query()->find($commentId)->with('article')->firstOrFail();
        $replay = new Comment();
        $replay->article()->associate($comment->article);
        $replay->content = $content;
        $replay->parent_comment_id = $commentId;
        $replay->user_id = Auth::userId();
        $replay->saveOrFail();
    }

    public function detail(int $commentId)
    {
        $comment = Comment::query()->find($commentId)->with('author')->firstOrFail();
        $replyList = $this->replyList($commentId);
        $article = ArticleServiceFacade::getArticleSimple($comment->article_id);
        $comment->article = $article;
        $comment->replyList = $replyList;
        return $comment;
    }
}