<?php
declare (strict_types=1);


namespace App\Service;
use Psr\Container\ContainerInterface;
use ZYProSoft\Service\AbstractService;

class BaseService extends AbstractService
{
    /**
     * @var \App\Service\JobDispatchService
     */
    protected $jobDispatcher;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->jobDispatcher = $this->container->get(JobDispatchService::class);
    }
}