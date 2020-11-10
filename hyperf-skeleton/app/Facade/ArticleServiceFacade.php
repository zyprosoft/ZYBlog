<?php


namespace App\Facade;
use HyperfFacade\Facade;
use HyperfFacade\Annotation\Alias;

/**
 * @Alias("Article")
 * Class ArticleServiceFacade
 * @package App\Facade
 */
class ArticleServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Service\ArticleService::class;
    }
}