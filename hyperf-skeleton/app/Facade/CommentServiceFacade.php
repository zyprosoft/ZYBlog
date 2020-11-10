<?php


namespace App\Facade;

use HyperfFacade\Facade;
use HyperfFacade\Annotation\Alias;

/**
 * @Alias("Comment")
 * Class CommentServiceFacade
 * @package App\Facade
 */
class CommentServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Service\CommentService::class;
    }
}