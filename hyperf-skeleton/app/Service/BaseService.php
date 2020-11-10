<?php


namespace App\Service;

class BaseService
{
    /**
     * @var \App\Service\JobDispatchService
     */
    protected $jobDispatcher;

    public function __construct(JobDispatchService $jobDispatcher)
    {
        $this->jobDispatcher = $jobDispatcher;
    }
}