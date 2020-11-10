<?php


namespace App\Service;


use App\Model\Article;
use App\Model\Tag;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Arr;
use ZYProSoft\Facade\Auth;
use ZYProSoft\Log\Log;

class ArticleService extends BaseService
{
    public function createArticle(string $title, string $content, array $tags, int $categoryId)
    {
        //先创建标签
        if (!empty($tags)) {
            Log::info("save tags:".json_encode($tags));
            $saveTags = collect($tags)->mapWithKeys(function ($value, $Key){
               return ['name'=>$value];
            })->toArray();
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

    public function deleteArticle(int $articleId)
    {

    }
}