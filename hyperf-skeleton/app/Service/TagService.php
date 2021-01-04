<?php


namespace App\Service;


use App\Model\ArticleTag;
use App\Model\Tag;
use Hyperf\DbConnection\Db;

class TagService extends BaseService
{
    public function getAll()
    {
        return Tag::all();
    }

    public function create(string $name)
    {
        Tag::insertOrIgnore([
            'name' => $name
        ]);
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
}