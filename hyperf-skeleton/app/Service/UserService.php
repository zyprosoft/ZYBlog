<?php


namespace App\Service;


use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use Hyperf\DbConnection\Db;
use ZYProSoft\Facade\Auth;

class UserService extends BaseService
{
    public function login(string $username, string $password)
    {
        $user = Db::table('user')->where('username', $username)
                                       ->where('password', password_hash($password, PASSWORD_DEFAULT))
                                       ->first();
        if (!$user instanceof User) {
            throw new BusinessException(ErrorCode::USER_ERROR_PASSWORD_WRONG);
        }

        $user->token = Auth::login($user);

        return $user;
    }
}