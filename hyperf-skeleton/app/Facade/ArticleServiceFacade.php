<?php


namespace App\Facade;
use App\Service\ArticleService;
use Hyperf\Utils\ApplicationContext;

class ArticleServiceFacade extends ArticleService
{
    public static function __callStatic($name, $arguments)
    {
        $service = ApplicationContext::getContainer()->get(ArticleService::class);
        $callback = [$service,$name];
        call($callback, $arguments);
    }
}