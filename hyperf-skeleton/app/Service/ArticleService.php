<?php

declare(strict_types=1);

namespace App\Service;


use App\Model\Article;
use App\Model\ArticleTag;
use App\Model\Comment;
use App\Model\Tag;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;
use ZYProSoft\Log\Log;
use Hyperf\Cache\Annotation\Cacheable;

class ArticleService extends BaseService
{
    public function createArticle(string $title, string $content, array $tags, int $categoryId)
    {
        //先创建标签
        if (!empty($tags)) {
            $saveTags = collect($tags)->map(function ($value) {
                return ['name' => $value];
            })->toArray();
            Log::info("save tags:" . json_encode($saveTags));
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
        })->orderByDesc('created_at')
            ->offset($pageIndex * $pageSize)
            ->limit($pageSize)
            ->with(['author', 'tags', 'category'])
            ->get();

        $total = Article::count('*');

        return ['total' => $total, 'list' => $list];
    }

    /**
     * @param int $articleId
     * @return Builder|Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Model\Model|\Hyperf\Database\Query\Builder|null
     */
    public function getArticleDetail(int $articleId)
    {
        return Article::query()->select()
            ->where('article_id', $articleId)
            ->with(['author', 'tags', 'category', 'comments'])
            ->firstOrFail();
    }

    public function updateArticle(int $articleId, string $title = null, string $content = null, array $tags = null, int $categoryId = null)
    {
        Db::transaction(function () use ($articleId, $title, $content, $categoryId, $tags) {

            $update = [];
            if (isset($title)) {
                $update['title'] = $title;
            }
            if (isset($content)) {
                $update['content'] = $content;
            }
            if (isset($categoryId)) {
                $update['category_id'] = $categoryId;
            }

            Article::query()->select()
                ->where('article_id', $articleId)
                ->update($update);

            //获取Tags
            if (!empty($tags)) {

                $saveTags = collect($tags)->map(function ($value) {
                    return ['name' => $value];
                })->toArray();
                Log::info("save tags:" . json_encode($saveTags));
                Tag::insertOrIgnore($saveTags);

                $tagList = Tag::query()->whereIn('name', $tags)->get();
                ArticleTag::query()->select(['tag_id'])
                    ->where('article_id', $articleId)
                    ->delete();

                $article = Article::findOrFail($articleId);
                $article->tags()->saveMany($tagList);
            }
        });
    }

    /**
     * 移动到回收站
     * @param int $articleId
     */
    public function moveToTrash(int $articleId)
    {
        Article::find($articleId)->delete();
    }

    public function getArticleListByCreateTime(int $pageIndex, int $pageSize, string $createAt)
    {
        $createTime = date('Y-m', strtotime($createAt));
        $list = Article::query()->where('created_at', 'like', "%$createTime%")
            ->with(['author', 'category', 'tags'])
            ->offset($pageIndex * $pageSize)
            ->limit($pageSize)
            ->orderByDesc('created_at')
            ->get();
        $total = Article::query()->where('created_at', 'like', "%$createTime%")
            ->count();
        return ['total' => $total, 'list' => $list];
    }

    public function getArticleListByTag(int $pageIndex, int $pageSize, int $tagId)
    {
        $relationList = ArticleTag::query()
            ->leftJoin('article','article_tag.article_id','=','article.article_id')
            ->where('tag_id', $tagId)
            ->whereNull('article.deleted_at')
            ->whereNotNull('article.article_id')
            ->limit($pageSize)
            ->offset($pageIndex * $pageSize)
            ->orderByDesc('article_tag.created_at')
            ->get();

        $articleIds = $relationList->pluck('article_id')->values();
        $articleList = Article::query()->whereIn('article_id', $articleIds)
            ->with(['author', 'category', 'tags'])
            ->get()
            ->keyBy('article_id')
            ->toArray();

        $relationList->map(function ($item) use ($articleList) {
            $article = $articleList[$item['article_id']];
            array_map(function ($key, $value) use ($item) {
               $item[$key] = $value;
            }, array_keys($article), $article);
        });

        $total = ArticleTag::query()->where('tag_id', $tagId)
            ->count();

        return ['total' => $total, 'list' => $relationList];
    }

    /**
     * @Cacheable(prefix="articleList", ttl=7200, listener="ListRecent")
     * @param int $pageIndex
     * @param int $pageSize
     * @return array
     */
    public function getArticleListByRecentPost(int $pageIndex, int $pageSize)
    {
        $articleList = Article::query()->with(['author', 'category', 'tags'])
            ->limit($pageSize)
            ->offset($pageIndex * $pageSize)
            ->orderByDesc('created_at')
            ->get();
        $total = Article::count();

        return ['total' => $total, 'list' => $articleList];
    }

    public function getArticleListByRecentComment(int $pageIndex, int $pageSize)
    {
        $commentList = Comment::query()->selectRaw('distinct article_id')
            ->addSelect(['created_at'])
            ->offset($pageIndex * $pageSize)
            ->limit($pageSize)
            ->latest()
            ->get();

        $total = Article::count();

        $articleIds = $commentList->pluck('article_id')->values();
        $articleList = Article::query()->where('article_id', $articleIds)
            ->with(['author', 'tags', 'category'])
            ->get();

        return ['total' => $total, 'list' => $articleList];
    }

    public function getAllArchivedMonth()
    {
        return Article::query()->selectRaw("distinct DATE_FORMAT(created_at, '%Y年%m月') as date")
                                ->orderByDesc('date')
                                ->get();
    }
}
