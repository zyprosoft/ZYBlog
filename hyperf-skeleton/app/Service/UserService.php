<?php


namespace App\Service;


use App\Constants\ErrorCode;
use ZYProSoft\Constants\ErrorCode as ZYErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use Hyperf\DbConnection\Db;
use ZYProSoft\Facade\Auth;
use ZYProSoft\Log\Log;

class UserService extends BaseService
{
    public function login(string $username, string $password)
    {
        $user = User::query()->where('username', $username)
                             ->first();
        if (!$user instanceof User) {
            throw new BusinessException(ZYErrorCode::RECORD_NOT_EXIST,"用户不存在!");
        }
        $verify = password_verify($password, $user->password);
        if (!$verify) {
            throw new BusinessException(ZYErrorCode::DB_ERROR,"密码验证错误");
        }

        $user->token = Auth::login($user);

        return $user;
    }
}