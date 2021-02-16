<?php
/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  ZYProSoft
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
    private TagService $tagService;

    /**
     * 创建一个标签
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(AppAdminRequest $request)
    {
        $this->validate([
            'name' => 'required|string|min:2'
        ]);
        $name = $request->param('name');
        $this->tagService->create($name);
        return $this->success();
    }

    /**
     * 获取所有标签
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAll(AppAdminRequest $request)
    {
        $list = $this->tagService->getAll();
        return $this->success($list);
    }

    /**
     * 删除一个标签
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function delete(AppAdminRequest $request)
    {
        $this->validate([
            'tagId' => 'required|int|min:1|exists:tag,tag_id'
        ]);
        $tagId = $request->param('tagId');
        $this->tagService->delete($tagId);
        return $this->success();
    }

    /**
     * 更新一个标签
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
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