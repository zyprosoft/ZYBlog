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


namespace App\Job;
use App\Model\Article;
use App\Model\Comment;
use Hyperf\AsyncQueue\Job;
use Hyperf\DbConnection\Db;
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
        Db::transaction(function (){
            $commentCount = Comment::query()->where('article_id', $this->articleId)->count();
            Log::info("find article comment count:".$commentCount);
            Article::query()->where('article_id', $this->articleId)->update(['comment_count'=>$commentCount]);
            Log::info("success update article comment count in async queue!");
        });
    }
}