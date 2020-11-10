<?php


namespace App\Service;


use App\Facade\CommentServiceFacade;
use App\Model\Article;
use App\Model\Tag;
use Hyperf\Database\Model\Builder;
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
            $article->saveOrFail();
            $article->tags()->saveMany($tagList);

        });
    }

    public function getArticleList(int $pageIndex, int $pageSize, int $categoryId = null)
    {
       return  Article::query()->where(function (Builder $query) use ($categoryId) {
            if (isset($categoryId)) {
                $query->where('category_id', $categoryId);
            }
        })->offset($pageIndex * $pageSize)->limit($pageSize)->with(['author','tags','category'])->get();
    }

    /**
     * @param int $articleId
     * @return Builder|Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Model\Model|\Hyperf\Database\Query\Builder|null
     */
    public function getArticleDetail(int $articleId)
    {
        $article = Article::query()->find($articleId)
                                   ->with(['author','category','tags'])
                                   ->first();
        $commentList = CommentServiceFacade::list(0,10, $articleId);
        $article->comments = $commentList;
        return $article;
    }

    /**
     * @param int $articleId
     * @return Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function getArticleSimple(int $articleId)
    {
        return Article::query()->select(['title','user_id','category_id'])
                                   ->where('article_id', $articleId)
                                   ->with(['author','category','tags'])
                                   ->first();
    }

    public function deleteArticle(int $articleId)
    {

    }
}