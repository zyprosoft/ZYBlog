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

    public function getAll(AppAdminRequest $request)
    {
        $list = $this->tagService->getAll();
        return $this->success($list);
    }

    public function delete(AppAdminRequest $request)
    {
        $this->validate([
            'tagId' => 'required|int|min:1|exists:tag,tag_id'
        ]);
        $tagId = $request->param('tagId');
        $this->tagService->delete($tagId);
        return $this->success();
    }

    public function update(AppAdminRequest  $request)
    {
        $this->validate([
            'name' => 'required|string|min:1',
            'tagId' => 'required|int|min:1|exists:tag,tag_id'
        ]);
        $tagId = $request->param('tagId');
        $name = $request->param('name');
        $this->tagService->update($name, $tagId);
        return $this->success();
    }
}