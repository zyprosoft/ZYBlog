<?php


namespace App\Service;
use _HumbugBox5d215ba2066e\phpDocumentor\Reflection\Types\Array_;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Job\RefreshArticleJob;
use App\Model\Article;
use App\Model\Comment;
use App\Model\User;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Arr;
use Psr\Container\ContainerInterface;
use ZYProSoft\Entry\EmailAddressEntry;
use ZYProSoft\Entry\EmailEntry;
use ZYProSoft\Log\Log;
use Hyperf\Cache\Annotation\Cacheable;

class CommentService extends BaseService
{
    private $clearListPageSize = 10;

    public function create(int $articleId, string $content, string $nickname, string $email, string $avatar = null, string $site = null, int $parentCommentId = null)
    {
        $comment = new Comment();
        $comment->article_id = $articleId;
        $comment->content = $content;

        Db::transaction(function () use ($articleId, $comment, $nickname, $email, $avatar, $site, $parentCommentId){

            //查找用户是否存在
            $user = User::query()->firstOrCreate([
                'email' => $email
            ], [
                'nickname' => $nickname,
                'site' => $site,
                'avatar' => $avatar
            ]);
            if (! $user instanceof User) {
                throw new BusinessException(ErrorCode::COMMENT_USER_CREATE_NEW_FAIL);
            }
            $comment->user_id = $user->user_id;

            if (isset($parentCommentId)) {
                $comment->parent_comment_id = $parentCommentId;
            }
            Log::info("will save comment:".$comment->toJson());
            $comment->saveOrFail();
            //绑定作者信息
            $comment->author = $user;

        });

        $user = User::query()->where('email', $email)->first();
        $article = Article::find($articleId);

        //给作者发邮件
        $emailToAuthor = new EmailEntry();
        $articleTitle = $article->title;
        $emailToAuthor->subject = "【ZYProSoft博客】"."你的文章《".$articleTitle."》收到了新的评论!";
        $articleUrl = 'http://'.env('HOST').'/article/'.$articleId;
        $checkDetail = "<a href=\"$articleUrl\">点击查看详情</a>";
        $autoSendTip = "本邮件由ZYVincent代发，如有疑问请联系QQ:1003081775，谢谢!";
        $toAuthorContent = "用户<".$user->nickname.">评论了你的文章《".$articleTitle."》:".$comment->content;
        $emailToAuthor->body = $toAuthorContent."</br>".$checkDetail."</br>".$autoSendTip;
        $author = User::find($article->user_id);
        $authorAddress = new EmailAddressEntry($author->email,$author->nickname);
        $emailToAuthor->receivers[] = $authorAddress;
        $emailToAuthor->replyTo = $authorAddress;
        $from = config('hyperf-common.mail.smtp.username');
        $fromName = env('MAIL_FROM_NAME', 'zyprosoft');
        $fromAddress = new EmailAddressEntry($from, $fromName);
        $emailToAuthor->from = $fromAddress;
        $this->asyncSendEmail($emailToAuthor);

        //给收到回复的人发邮件
        if (isset($parentCommentId)) {
            $emailToReplyUser = new EmailEntry();
            $parentComment = Comment::query()->where('comment_id', $parentCommentId)->with(['author'])->first();
            $emailToReplyUser->subject = "【ZYProSoft博客】你收到了新的评论回复!";
            $toReplyUserContent = "你在文章《".$articleTitle."》的评论(".$parentComment->content.")收到了新的回复，</br>用户(".$user->nickname.")说:".$content;
            $emailToReplyUser->body = $toReplyUserContent."</br>".$checkDetail."</br>".$autoSendTip;
            $replyAddress = new EmailAddressEntry($parentComment->author->email, $parentComment->author->nickname);
            $emailToReplyUser->replyTo = $replyAddress;
            $emailToReplyUser->receivers[] = $replyAddress;
            $emailToReplyUser->from = $fromAddress;
            $this->asyncSendEmail($emailToReplyUser);
        }

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
                               ->with(['author', 'parent_comment'])
                               ->offset($pageIndex * $pageSize)
                               ->limit($pageSize)
                               ->latest()
                               ->get();

        //给parent_comment获取用户信息
        $userIds = $list->where('parent_comment_id','!==', null)->pluck('parent_comment.user_id');
        Log::info("all parent comment userIds:".$userIds->toJson());
        $userList = User::findMany($userIds)->keyBy('user_id');
        $list->map(function (Comment $comment) use ($userList) {
            if (!is_null($comment->parent_comment_id)) {
                $comment->parent_comment->author = Arr::get($userList, $comment->parent_comment->user_id);
            }
        });

        $total = Comment::query()->where('article_id', $articleId)
                                 ->count();
        return ['total' => $total, 'list' => $list];
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
                                   ->with(['author','reply_list'])
                                   ->firstOrFail();
        $article = Article::query()->select(['article_id','title','user_id','category_id'])
                                   ->where('article_id', $comment->article_id)
                                   ->with(['author','category','tags'])
                                   ->first();
        $comment->article = $article;
        return $comment;
    }

    public function list(int $pageIndex, int $pageSize)
    {
        $list = Comment::query()->with(['article','author','parent_comment'])
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