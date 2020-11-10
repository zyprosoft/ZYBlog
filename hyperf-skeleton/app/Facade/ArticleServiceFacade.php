<?php


namespace App\Facade;
use App\Service\ArticleService;
use Hyperf\Database\Model\Builder;
use Hyperf\Utils\ApplicationContext;

/**
 * @method Builder|\Hyperf\Database\Model\Model|object|null getArticleSimple(int $articleId)
 * Class ArticleServiceFacade
 * @package App\Facade
 */
class ArticleServiceFacade
{
    public static function __callStatic($name, $arguments)
    {
        $service = ApplicationContext::getContainer()->get(ArticleService::class);
        $callback = [$service,$name];
        call($callback, $arguments);
    }
}