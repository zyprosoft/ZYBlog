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

namespace App\Controller\Common;
use App\Service\ArticleService;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use ZYProSoft\Controller\AbstractController;

/**
 * @AutoController(prefix="/common/article")
 * Class ArticleController
 * @package App\Controller\Common
 */
class ArticleController extends AbstractController
{
    /**
     * @Inject
     * @var ArticleService
     */
    private ArticleService $articleService;

    /**
     * 可指定分类进行的文章列表获取
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function list()
    {
        $pageIndex = $this->request->param('pageIndex', 0);
        $pageSize = $this->request->param('pageSize', 20);
        $categoryId = null;
        if ($this->request->hasParam('categoryId')) {
            $this->validate([
                'categoryId' => 'integer|min:1|exists:category,category_id'
            ]);
            $categoryId = $this->request->param('categoryId');
        }
        $articleList = $this->articleService->getArticleList($pageIndex, $pageSize, $categoryId);
        return $this->success($articleList);
    }

    /**
     * 获取文章详情
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function detail()
    {
        $this->validate([
            'articleId' => 'integer|exists:article,article_id|required',
        ]);
        $articleId = $this->request->param('articleId');
        $article = $this->articleService->getArticleDetail($articleId);
        return $this->success($article);
    }

    /**
     * 按照归档月份获取文章列表
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getListByDate()
    {
        $this->validate([
            'pageIndex' => 'integer|required|min:0',
            'pageSize' => 'integer|max:30|required',
            'date' => 'string|date|date_format:Y-m|required'
        ]);
        $pageIndex = $this->request->param('pageIndex', 0);
        $pageSize = $this->request->param('pageSize', 20);
        $date = $this->request->param('date');
        $articleList = $this->articleService->getArticleListByCreateTime($pageIndex, $pageSize, $date);
        return $this->success($articleList);
    }

    /**
     * 按照标签获取文章列表
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getListByTagId()
    {
        $this->validate([
            'pageIndex' => 'integer|required|min:0',
            'pageSize' => 'integer|max:30|required',
            'tagId' => 'integer|min:1|required|exists:tag,tag_id'
        ]);
        $pageIndex = $this->request->param('pageIndex', 0);
        $pageSize = $this->request->param('pageSize', 20);
        $tagId = $this->request->param('tagId');
        $articleList = $this->articleService->getArticleListByTag($pageIndex, $pageSize, $tagId);
        return $this->success($articleList);
    }

    /**
     * 按照最近发表获取文章列表
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getListByRecentPost()
    {
        $this->validate([
            'pageIndex' => 'integer|required|min:0',
            'pageSize' => 'integer|max:30|required',
        ]);
        $pageIndex = $this->request->param('pageIndex', 0);
        $pageSize = $this->request->param('pageSize', 20);
        $articleList = $this->articleService->getArticleListByRecentPost($pageIndex, $pageSize);
        return $this->success($articleList);
    }

    /**
     * 按照最后回复获取文章列表
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getListByRecentComment()
    {
        $this->validate([
            'pageIndex' => 'integer|required|min:0',
            'pageSize' => 'integer|max:30|required',
        ]);
        $pageIndex = $this->request->param('pageIndex', 0);
        $pageSize = $this->request->param('pageSize', 20);
        $articleList = $this->articleService->getArticleListByRecentComment($pageIndex, $pageSize);
        return $this->success($articleList);
    }

    /**
     * 获取所有文章归档月份
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getArchiveDateList()
    {
        $list = $this->articleService->getAllArchivedMonth();
        return $this->success($list);
    }
}