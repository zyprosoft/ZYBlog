<?php


namespace App\Controller\Common;
use App\Component\OneSentenceComponent;
use ZYProSoft\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Di\Annotation\Inject;
use App\Service\CommonService;

/**
 * @AutoController(prefix="/common/about")
 * Class AboutController
 * @package App\Controller\Common
 */
class AboutController extends AbstractController
{
    /**
     * @Inject
     * @var CommonService
     */
    private $service;

    public function info()
    {
        return $this->success($this->service->getAboutInfo());
    }

    public function getOneSentence()
    {
        $component = make(OneSentenceComponent::class);
        $result = $component->getOneSentence();
        return $this->success($result->successOrFailException());
    }
}