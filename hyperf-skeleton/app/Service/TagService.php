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

namespace App\Service;


use App\Model\ArticleTag;
use App\Model\Tag;
use Hyperf\DbConnection\Db;
use ZYProSoft\Log\Log;
use Hyperf\Cache\Annotation\Cacheable;

class TagService extends BaseService
{
    public function getAll()
    {
        return Tag::all();
    }

    public function create(string $name)
    {
        $tag = new Tag();
        $tag->name = $name;
        $tag->saveOrFail();
    }

    public function delete(int $tagId)
    {
        Db::transaction(function () use ($tagId) {
            $tag = Tag::findOrFail($tagId);
            ArticleTag::query()->select()->where('tag_id', $tagId)->delete();
            $tag->delete();
        });
    }

    public function update(string $name, int $tagId)
    {
        Tag::query()->select()->where('tag_id', $tagId)->update(['name'=>$name]);
    }

    /**
     * @Cacheable(prefix="hot-tag", ttl=3600, listener="hotTagUpdate")
     * @return Tag[]|\Hyperf\Database\Model\Collection
     */
    public function getHotTags()
    {
       $tagList =  ArticleTag::query()->selectRaw("count('distinct article_id') as count, tag_id")
                          ->leftJoin('article','article_tag.article_id','=','article.article_id')
                          ->whereNull('article.deleted_at')
                          ->whereNotNull('article.article_id')
                          ->groupBy(['tag_id'])
                          ->limit(6)
                          ->orderByDesc("count")
                          ->with(['tag'])
                          ->get();
       Log::info('tagList:'.json_encode($tagList));

       return $tagList;
    }
}