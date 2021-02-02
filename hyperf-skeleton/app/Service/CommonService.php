<?php


namespace App\Service;
use App\Model\About;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Str;

class CommonService extends BaseService
{
    public function getAboutInfo()
    {
        return About::first();
    }

    public function clearSystemCache()
    {
        $this->clearAllCache();
        return $this->success();
    }

    public function commitAboutInfo(array $aboutInfo)
    {
        $about = About::query()->where('email', Arr::get($aboutInfo, 'email'))->firstOrFail();
        array_map(function ($key,$value) use ($about) {
            if (isset($value)) {
                $column = Str::snake($key);
                $about->$column = $value;
            }
        }, array_keys($aboutInfo), $aboutInfo);
        $about->save();
        return $this->success();
    }
}