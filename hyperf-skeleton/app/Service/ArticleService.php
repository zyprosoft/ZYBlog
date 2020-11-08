<?php


namespace App\Service;


use App\Model\Article;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\Arr;
use ZYProSoft\Facade\Auth;

class ArticleService extends BaseService
{
    public function createArticle(string $title, string $content, array $tags, int $categoryId)
    {
        Db::transaction(function () use ($title, $content, $categoryId, $tags) {

            //先创建标签
            if (!empty($tags)) {
                $saveTags = collect($tags)->mapWithKeys(function ($value, $key) {
                    return ['name' => $value];
                })->toArray();
                Db::table('tag')->updateOrInsert($saveTags);
            }

            //获取Tags
            $tagList = Db::table('tag')->whereIn('name', $tags)->get();

            $article = new Article();
            $article->title = $title;
            $article->content = $content;
            $userId = Auth::user()->getId();
            $article->user_id = $userId;
            $article->category_id = $categoryId;
            $article->tags()->attach($tagList);
            $article->save();

        });
    }

    public function deleteArticle(int $articleId)
    {

    }
}