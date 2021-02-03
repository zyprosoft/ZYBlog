<?php


namespace App\Service;
use App\Model\Article;
use App\Model\Category;
use Hyperf\DbConnection\Db;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Utils\Arr;

class CategoryService extends BaseService
{
    /**
     * @Cacheable(prefix="category-all", ttl=3600, listener="categoryAllUpdate")
     * @return Category[]|\Hyperf\Database\Model\Collection
     */
    public function getAll()
    {
        $list = Category::all();
        //统计每个分类的文章数量
        $countList = Article::query()->selectRaw("category_id,count(*) as total")
                                    ->groupBy(['category_id'])
                                    ->get()
                                    ->keyBy('category_id');
        $list->map(function (Category $category) use ($countList) {
            $category['total'] = Arr::get($countList, $category->category_id)->total;
        });
        return $list;
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