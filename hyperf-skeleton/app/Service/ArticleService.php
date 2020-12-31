<?php
declare(strict_types=1);

namespace App\Service;


use App\Model\Article;
use App\Model\ArticleTag;
use App\Model\Comment;
use App\Model\Tag;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;
use ZYProSoft\Exception\HyperfCommonException;
use ZYProSoft\Log\Log;
use ZYProSoft\Constants\ErrorCode;

class ArticleService extends BaseService
{
    public function createArticle(string $title, string $content, array $tags, int $categoryId)
    {
        //先创建标签
        if (!empty($tags)) {
            $saveTags = collect($tags)->map(function ($value) {
                return ['name' => $value];
            })->toArray();
            Log::info("save tags:".json_encode($saveTags));
            Tag::insertOrIgnore($saveTags);
        }

        Db::transaction(function () use ($title, $content, $categoryId, $tags) {

            //获取Tags
            $tagList = Tag::query()->whereIn('name', $tags)->get();

            $article = new Article();
            $article->title = $title;
            $article->content = $content;
            $article->user_id = $this->userId();
            $article->category_id = $categoryId;
            $article->saveOrFail();
            $article->tags()->saveMany($tagList);

        });
    }

    public function getArticleList(int $pageIndex, int $pageSize, int $categoryId = null)
    {
       $list =  Article::query()->where(function (Builder $query) use ($categoryId) {
            if (isset($categoryId)) {
                $query->where('category_id', $categoryId);
            }
        })->offset($pageIndex * $pageSize)->limit($pageSize)->with(['author','tags','category'])->get();

       $total = Article::count('*');

       return ['total'=>$total, 'list'=>$list];
    }

    /**
     * @param int $articleId
     * @return Builder|Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Model\Model|\Hyperf\Database\Query\Builder|null
     */
    public function getArticleDetail(int $articleId)
    {
        $article = Article::query()->find($articleId);
        //通过访问加载出来对应的关联属性
        $article->author;
        $article->tags;
        $article->category;

        //获取一页评论列表
        $commentList = Comment::query()->where('article_id',$articleId)
                                       ->with(['author','parentComment'])
                                       ->offset(0)
                                       ->limit(10)->get();

        $article->comments = $commentList;

        return $article;
    }

    public function updateArticle(int $articleId, string $title = null, string $content = null, array $tags = null, int $categoryId = null)
    {
        $article = Article::query()->find($articleId);
        if (!$article instanceof Article) {
            throw new HyperfCommonException(ErrorCode::RECORD_NOT_EXIST,'文章不存在');
        }

        //先创建标签
        if (!empty($tags)) {
            $saveTags = collect($tags)->map(function ($value) {
                return ['name' => $value];
            })->toArray();
            Log::info("save tags:".json_encode($saveTags));
            Tag::insertOrIgnore($saveTags);
        }

        Db::transaction(function () use ($article, $title, $content, $categoryId, $tags) {

            $article->title = $title??$article->title;
            $article->content = $content??$article->content;
            $article->category_id = $categoryId??$article->category_id;

            //获取Tags
            if (!empty($tags)) {
                $tagList = Tag::query()->whereIn('name', $tags)->get();
                ArticleTag::query()->select(['tag_id'])->where('article_id',$article->article_id)->delete();
            }

            $article->saveOrFail();

            if (!empty($tags)) {
                $article->tags()->saveMany($tagList);
            }
        });
    }
}