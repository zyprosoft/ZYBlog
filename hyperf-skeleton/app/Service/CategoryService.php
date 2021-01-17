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

    public function create(string $name, string $icon = null)
    {
        $category = new Category();
        $category->name = $name;
        $category->avatar = $icon;
        $category->saveOrFail();
    }

    public function delete(int $categoryId)
    {
        //删除分类，移动分类下文章到第一个分类
        Db::transaction(function () use ($categoryId) {
            $article = Category::query()->findOrFail($categoryId);
            $firstCategory = Category::query()->firstOrFail();
            Article::query()->select()->where('category_id', $categoryId)->update(['category_id'=>$firstCategory->category_id]);
            $article->delete();
        });
    }

    /**
     * 更新分类
     *
     * @param integer $categoryId
     * @param string $name
     * @param string $avatar
     * @return void
     */
    public function update(int $categoryId, string $name = null, string $avatar = null )
    {
        $update = [];
        if (isset($name)) {
            $update['name'] = $name;
        }
        if (isset($avatar)) {
            $update['avatar'] = $avatar;
        }
        if (empty($update)) {
            return;
        }
       Category::query()->select()->where('category_id', $categoryId)->update($update);
    }

    public function getAllArchiveDate()
    {
        return Article::query()->selectRaw("distinct DATE_FORMAT(`created_at`,'%Y-%m') as archive_month")
                                ->get();
    }
}