<?php


namespace App\Job;
use App\Model\Article;
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
        $article = Article::query()->find($this->articleId)->with('comments')->count();
        if (!$article instanceof Article) {
            Log::info("async refresh article without find article!");
            return;
        }
        Log::info("find article:".json_encode($article));
    }
}