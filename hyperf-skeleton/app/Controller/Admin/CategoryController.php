<?php
/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  ZYProSoft
 * @license  MIT
 */
declare(strict_types=1);

namespace App\Controller\Admin;
use App\Http\AppAdminRequest;
use App\Service\CommonService;
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use App\Service\CategoryService;

/**
 * @AutoController(prefix="/admin/category")
 * Class CategoryController
 * @package App\Controller\Admin
 */
class CategoryController extends AbstractController
{
    /**
     * @Inject
     * @var CategoryService
     */
    private CategoryService $categoryService;

    /**
     * @Inject
     * @var CommonService
     */
    private CommonService $commonService;

    /**
     * 获取所有分类
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAll(AppAdminRequest $request)
    {
        return $this->success($this->categoryService->getAll());
    }

    /**
     * 创建一个分类
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(AppAdminRequest $request)
    {
        $this->validate([
            'name' => 'required|string|min:2|max:10',
            'icon' => 'string|min:1'
        ]);
        $name = $request->param('name');
        $icon = $request->param('icon');
        $this->categoryService->create($name, $icon);
        return $this->success();
    }

    /**
     * 删除一个分类，删除后，在此分类下的文章自动转移到编号最小的分类
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(AppAdminRequest $request)
    {
        $this->validate([
            'categoryId' => 'required|int|min:1|exists:category,category_id'
        ]);
        $categoryId = $request->param('categoryId');
        $this->categoryService->delete($categoryId);
        return $this->success();
    }

    /**
     * 更新分类信息
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(AppAdminRequest $request)
    {
        $this->validate([
            'name' => 'string|min:1|max:10',
            'avatar' => 'string|min:1',
            'categoryId' => 'required|int|min:1|exists:category,category_id'
        ]);
        $categoryId = $request->param('categoryId');
        $name = $request->param('name');
        $avatar = $request->param('avatar');
        $this->categoryService->update($categoryId, $name, $avatar);
        //清空系统缓存
        $this->commonService->clearSystemCache();
        return $this->success();
    }
}