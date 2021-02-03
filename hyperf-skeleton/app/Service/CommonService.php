<?php


namespace App\Service;
use App\Constants\Constants;
use App\Model\About;
use App\Model\User;

class CommonService extends BaseService
{
    public function getAboutInfo()
    {
        return User::query()->where('role_id', Constants::USER_ROLE_ADMIN)->with(['about'])->firstOrFail();
    }

    public function clearSystemCache()
    {
        //清空文章缓存
        $this->clearCachePrefix('article-list');
        $this->clearCachePrefix('article-detail');
        $this->clearCachePrefix('article-archive');

        //清空评论缓存
        $this->clearCachePrefix('comment-list-each');

        return $this->success();
    }

    public function commitAboutInfo(array $aboutInfo)
    {
        $user = User::findOrFail($this->userId());
        $userKeys = ['nickname','avatar','email'];
        array_map(function ($key) use (&$user, &$aboutInfo){
            if (isset($aboutInfo[$key])) {
                $user->$key = $aboutInfo[$key];
                unset($aboutInfo[$key]);
            }
        },$userKeys);
        $user->saveOrFail();

        //保存扩展信息
        $about = About::query()->where('user_id', $user->user_id)->first();
        if(! $about instanceof About) {
            $about = new About();
            $about->user_id = $user->user_id;
        }
        array_map(function ($key,$value) use ($about) {
            if (isset($value)) {
                $about->$key = $value;
            }
        }, array_keys($aboutInfo), $aboutInfo);
        $about->saveOrFail();

        return $this->success();
    }
}