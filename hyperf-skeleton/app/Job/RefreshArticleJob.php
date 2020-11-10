<?php


namespace App\Job;
use App\Model\Article;
use App\Model\Comment;
use Hyperf\AsyncQueue\Job;
use ZYProSoft\Log\Log;

class RefreshArticleJob extends Job
{
    /**
     * @var int
     */
    private $articleId;

    public function __construct(int $articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * @inheritDoc
     */
    public function handle()
    {
        $commentCount = Comment::query()->where('article_id', $this->articleId)->count();
        Log::info("find article comment count:".$commentCount);
        Article::query()->where('article_id', $this->articleId)->update(['comment_count'=>$commentCount]);
        Log::info("success update article comment count in async queue!");
    }
}