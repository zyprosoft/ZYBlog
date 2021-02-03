<?php


namespace App\Service;
use App\Constants\Constants;
use App\Model\About;
use App\Model\User;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Str;

class CommonService extends BaseService
{
    public function getAboutInfo()
    {
        return User::query()->where('role_id', Constants::USER_ROLE_ADMIN)->with(['about'])->firstOrFail();
    }

    public function clearSystemCache()
    {
        $this->clearAllCache();
        return $this->success();
    }

    public function commitAboutInfo(array $aboutInfo)
    {
        $user = User::findOrFail($this->userId());
        $about = new About();
        array_map(function ($key,$value) use ($about) {
            if (isset($value)) {
                $about->$key = $value;
            }
        }, array_keys($aboutInfo), $aboutInfo);
        $user->about()->save($about);
        return $this->success();
    }
}