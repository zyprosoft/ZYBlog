<?php


namespace App\Facade;

use App\Service\CommentService;
use Hyperf\Utils\ApplicationContext;

/**
 * @method \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection list(int $pageIndex, int $pageSize, int $articleId)
 * Class CommentServiceFacade
 * @package App\Facade
 */
class CommentServiceFacade
{
    public static function __callStatic($name, $arguments)
    {
        $service = ApplicationContext::getContainer()->get(CommentService::class);
        $callback = [$service,$name];
        call($callback, $arguments);
    }
}