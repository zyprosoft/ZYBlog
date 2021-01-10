<?php


namespace App\Service;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Job\RefreshArticleJob;
use App\Model\Article;
use App\Model\Comment;
use Hyperf\DbConnection\Db;
use ZYProSoft\Log\Log;

class CommentService extends BaseService
{
    public function create(int $articleId, string $content, int $parentCommentId = null)
    {
        $comment = new Comment();
        $comment->user_id = $this->userId();
        $comment->article_id = $articleId;
        $comment->content = $content;
        if (isset($parentCommentId)) {
            $comment->parent_comment_id = $parentCommentId;
        }
        Log::info("will save comment:".$comment->toJson());
        $comment->saveOrFail();
        $this->jobDispatcher->push(new RefreshArticleJob($articleId));
    }

    /**
     * @param int $pageIndex
     * @param int $pageSize
     * @param int $articleId
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function listWithArticleId(int $pageIndex, int $pageSize, int $articleId)
    {
        $list = Comment::query()->where('article_id', $articleId)
                               ->with(['author', 'parentComment'])
                               ->offset($pageIndex * $pageSize)
                               ->limit($pageSize)->get();
        $total = Comment::query()->where('article_id', $articleId)
                                 ->count();
        return ['total' => $total, 'list' => $list];
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
        $replay->user_id = $this->userId();
        $replay->saveOrFail();
    }

    public function detail(int $commentId)
    {
        $comment = Comment::query()->select()->where('comment_id', $commentId)->with(['author','replyList'])->firstOrFail();
        $replyList = $this->replyList($commentId);
        $article = Article::query()->select(['article_id','title','user_id','category_id'])
                                   ->where('article_id', $comment->article_id)
                                   ->with(['author','category','tags'])
                                   ->first();
        $comment->article = $article;
        $comment->replyList = $replyList;
        return $comment;
    }

    public function list(int $pageIndex, int $pageSize)
    {
        $list = Comment::query()->with(['article','author','parentComment'])
                                ->orderByDesc('updated_at')
                                ->offset($pageIndex * $pageSize)
                                ->limit($pageSize)
                                ->get();
        $total = Comment::count();
        return ['total' => $total, 'list' => $list];
    }

    public function delete(int $commentId)
    {
        Db::transaction(function () use ($commentId) {
            $comment = Comment::findOrFail($commentId);
            $comment->article()->decrement('comment_count', 1);
            $comment->delete();
        });
    }

    public function userDelete(int $commentId)
    {
        $comment = Comment::query()->select()
                                   ->with(['author'])
                                   ->where('comment_id', $commentId)
                                   ->firstOrFail();
        if ($comment->author->user_id != $this->userId()) {
            throw new BusinessException(ErrorCode::USER_ACTION_NO_RIGHT);
        }

        Comment::find($commentId)->delete();
    }
}