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
use Hyperf\HttpServer\Annotation\AutoController;
use ZYProSoft\Controller\AbstractController;
use App\Http\AdminArticleRequest;
use Hyperf\Di\Annotation\Inject;
use App\Service\ArticleService;

/**
 * @AutoController(prefix="/admin/article")
 * Class ArticleController
 * @package App\Controller\Admin
 */
class ArticleController extends AbstractController
{
    /**
     * @Inject
     * @var ArticleService
     */
    private ArticleService $articleService;

    /**
     * 创建一篇博客接口
     * @param AdminArticleRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create(AdminArticleRequest $request)
    {
        $params = $request->validated();
        $title = $params['title'];
        $content = $params['content'];
        $tags = $params['tags'];
        $categoryId = $params['categoryId'];
        $this->articleService->createArticle($title, $content, $tags, $categoryId);
        return $this->success();
    }

    /**
     * 更新一篇博客接口
     * @param AdminArticleRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(AdminArticleRequest $request)
    {
        $params = $request->validated();
        $articleId = $params['articleId'];
        $title = $params['title'];
        $content = $params['content'];
        $tags = $params['tags'];
        $categoryId = $params['categoryId'];
        $this->articleService->updateArticle($articleId, $title, $content, $tags, $categoryId);
        return $this->success();
    }

    /**
     * 获取所有文章列表接口
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list(AppAdminRequest $request)
    {
        $this->validate([
            'pageIndex' => 'required|integer|min:0',
            'pageSize' => 'required|integer|min:1|max:30',
            'categoryId' => 'integer|min:1|exists:category,category_id',
        ]);
        $pageIndex = $request->param('pageIndex');
        $pageSize = $request->param('pageSize');
        $categoryId = null;
        if ($request->hasParam('categoryId')) {
            $categoryId = $request->param('categoryId');
        }
        $list = $this->articleService->getArticleList($pageIndex, $pageSize, $categoryId);
        return $this->success($list);
    }

    /**
     * 获取文章详情接口
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function detail(AppAdminRequest $request)
    {
        $this->validate([
            'articleId' => 'integer|exists:article,article_id|required',
        ]);
        $articleId = $request->param('articleId');
        $article = $this->articleService->getArticleDetail($articleId);
        return $this->success($article);
    }

    /**
     * 将文章软删除接口
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function moveToTrash(AppAdminRequest $request)
    {
        $this->validate([
            'articleId' => 'required|integer|min:1',
        ]);
        $articleId = $request->param('articleId');
        $this->articleService->moveToTrash($articleId);
        return $this->success();
    }

    /**
     * 获取所有带关键字"关于我"的文章列表，以供应用信息设置页面关联
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAboutArticleList(AppAdminRequest $request)
    {
        return $this->success($this->articleService->getAboutArticleList());
    }
}
