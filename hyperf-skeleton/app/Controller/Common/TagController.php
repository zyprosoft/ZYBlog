<?php


namespace App\Controller\Common;
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use App\Service\TagService;

/**
 * @AutoController(prefix="/common/tag")
 * Class TagController
 * @package App\Controller\Common
 */
class TagController extends AbstractController
{
    /**
     * @Inject
     * @var TagService
     */
    private $tagService;

    public function getAll()
    {
       $list = $this->tagService->getAll();
       return $this->success($list);
    }

    public function getHotTags() 
    {
        $list = $this->tagService->getHotTags();
        return $this->success($list);
    }
}