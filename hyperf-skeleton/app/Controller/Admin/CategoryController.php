<?php


namespace App\Controller\Admin;
use App\Http\AppAdminRequest;
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
    private $categoryService;

    public function create(AppAdminRequest $request)
    {
        $this->validate([
            'name' => 'required|string|min:2'
        ]);
        $name = $request->param('name');
        $this->categoryService->create($name);
        return $this->success();
    }

    public function delete(AppAdminRequest $request)
    {
        $this->validate([
            'categoryId' => 'required|int|min:1'
        ]);
        $categoryId = $request->param('categoryId');
        $this->categoryService->delete($categoryId);
        return $this->success();
    }

    public function update(AppAdminRequest $request)
    {
        $this->validate([
            'name' => 'string|min:1',
            'avatar' => 'string|min:1',
            'categoryId' => 'required|int|min:1'
        ]);
        $categoryId = $request->param('categoryId');
        $name = $request->param('name');
        $avatar = $request->param('avatar');
        $this->categoryService->update($categoryId, $name, $avatar);
        return $this->success();
    }
}