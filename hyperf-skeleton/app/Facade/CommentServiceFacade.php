<?php


namespace App\Facade;

use App\Service\CommentService;
use Hyperf\Utils\ApplicationContext;

class CommentServiceFacade extends CommentService
{
    public static function __callStatic($name, $arguments)
    {
        $service = ApplicationContext::getContainer()->get(CommentService::class);
        $callback = [$service,$name];
        call($callback, $arguments);
    }
}