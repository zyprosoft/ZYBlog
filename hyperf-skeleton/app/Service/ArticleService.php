<?php


namespace App\Service;


use App\Model\Article;
use App\Model\Tag;
use Hyperf\Database\Query\Builder;
use Hyperf\DbConnection\Db;
use ZYProSoft\Facade\Auth;
use ZYProSoft\Log\Log;

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
            $userId = Auth::userId();
            $article->user_id = $userId;
            $article->category_id = $categoryId;
            $article->save();
            $article->tags()->saveMany($tagList);

        });
    }

    public function getArticleList(int $pageIndex, int $pageSize, int $categoryId = null)
    {
       return  Article::query()->where(function (Builder $query) use ($categoryId) {
            if (isset($categoryId)) {
                $query->where('category_id', $categoryId);
            }
        })->paginate($pageSize,['*'],'page',$pageIndex);
    }

    public function deleteArticle(int $articleId)
    {

    }
}