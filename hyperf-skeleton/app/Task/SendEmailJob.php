<?php


namespace App\Task;


use App\Model\EmailEntry;
use App\Service\EmailService;
use Hyperf\AsyncQueue\Job;
use Hyperf\Utils\ApplicationContext;

class SendEmailJob extends Job
{
    private EmailEntry $emailEntry;

    public function __construct(EmailEntry $emailEntry)
    {
        $this->emailEntry = $emailEntry;
    }

    public function handle()
    {
        $service = ApplicationContext::getContainer()->get(EmailService::class);
        $service->sendEmail($this->emailEntry);
    }
}