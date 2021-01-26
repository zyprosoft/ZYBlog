<?php


namespace App\Service;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Job\RefreshArticleJob;
use App\Model\Article;
use App\Model\Comment;
use App\Model\User;
use Hyperf\DbConnection\Db;
use Psr\Container\ContainerInterface;
use ZYProSoft\Log\Log;
use Hyperf\Cache\Annotation\Cacheable;

class CommentService extends BaseService
{
    private $clearListPageSize = 10;

    public function create(int $articleId, string $content, string $nickname, string $email, string $site = null, int $parentCommentId = null)
    {
        $comment = new Comment();
        $comment->article_id = $articleId;
        $comment->content = $content;

        Db::transaction(function () use ($comment, $nickname, $email, $site, $parentCommentId){

            //查找用户是否存在
            $user = User::query()->firstOrCreate([
                'email' => $email
            ], [
                'nickname' => $nickname,
                'site' => $site
            ]);
            $comment->user_id = $user->user_id;

            if (isset($parentCommentId)) {
                $comment->parent_comment_id = $parentCommentId;
            }
            Log::info("will save comment:".$comment->toJson());
            $comment->saveOrFail();

            //绑定作者信息
            $comment->author = $user;

        });

        //刷新文章评论总数
        $this->push(new RefreshArticleJob($articleId));

        //清空这个文章的评论缓存
        $this->clearListCacheWithMaxPage('CommentListForEach', [$articleId], $this->clearListPageSize);

        //清除按照最新回复排列的文章列表缓存
        ArticleService::clearArticleListCachePrefix('article-list:comment');

        //清除文章详情缓存
        ArticleService::clearArticleDetailCache($articleId);

        return $comment;
    }

    /**
     * @Cacheable (prefix="comment-list-each", ttl=7200, listener="CommentListForEach")
     * @param int $pageIndex
     * @param int $pageSize
     * @param int $articleId
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function listWithArticleId(int $pageIndex, int $pageSize, int $articleId)
    {
        //清除缓存要用,只能设置成这个值
        if (isset($this->clearListPageSize)) {
            $pageSize = $this->clearListPageSize;
        }

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
        $comment = Comment::query()->where('comment_id', $commentId)
                                   ->with(['author','replyList'])
                                   ->firstOrFail();
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
        $comment = Comment::query()->with(['author'])
                                   ->where('comment_id', $commentId)
                                   ->firstOrFail();
        if ($comment->author->user_id != $this->userId()) {
            throw new BusinessException(ErrorCode::USER_ACTION_NO_RIGHT);
        }

        Comment::find($commentId)->delete();
    }
}