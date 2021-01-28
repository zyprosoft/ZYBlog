<?php


namespace App\Service;
use App\Model\About;

class CommonService extends BaseService
{
    public function getAboutInfo()
    {
        return About::first();
    }

    public function clearSystemCache()
    {
        $this->clearAllCache();
    }
}