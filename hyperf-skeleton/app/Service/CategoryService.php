<?php


namespace App\Service;
use App\Model\Article;
use App\Model\Category;
use Hyperf\DbConnection\Db;

class CategoryService extends BaseService
{
    public function getAll()
    {
        return Category::all();
    }

    public function create(string $name)
    {
        $category = new Category();
        $category->name = $name;
        $category->saveOrFail();
    }

    public function delete(int $categoryId)
    {
        //删除分类，移动分类下文章到第一个分类
        Db::transaction(function () use ($categoryId) {
            Category::query()->findOrFail($categoryId);
            $firstCategory = Category::query()->firstOrFail();
            Article::query()->select()->where('category_id', $categoryId)->update(['category_id'=>$firstCategory->category_id]);
        });
    }
}