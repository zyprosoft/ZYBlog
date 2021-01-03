<?php


namespace App\Controller\Admin;
use App\Http\AppAdminRequest;
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use App\Service\TagService;

/**
 * @AutoController(prefix="/admin/tag")
 * Class TagController
 * @package App\Controller\Admin
 */
class TagController extends AbstractController
{
    /**
     * @Inject
     * @var TagService
     */
    private $tagService;

    public function create(AppAdminRequest $request)
    {
        $this->validate([
            'name' => 'required|string|min:2'
        ]);
        $name = $request->param('name');
        $this->tagService->create($name);
        return $this->success();
    }

    public function delete(AppAdminRequest $request)
    {
        $this->validate([
            'tagId' => 'required|int|min:1'
        ]);
        $tagId = $request->param('tagId');
        $this->tagService->delete($tagId);
        return $this->success();
    }

    public function update(AppAdminRequest  $request)
    {
        $this->validate([
            'name' => 'required|string|min:1',
            'tagId' => 'required|int|min:1'
        ]);
        $tagId = $request->param('tagId');
        $name = $request->param('name');
        $this->tagService->update($name, $tagId);
        return $this->success();
    }
}