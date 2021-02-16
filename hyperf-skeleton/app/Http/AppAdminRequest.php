<?php

/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  ZYProSoft
 * @license  MIT
 */

namespace App\Http;


use App\Model\User;
use ZYProSoft\Facade\Auth;
use ZYProSoft\Http\AdminRequest;
use ZYProSoft\Log\Log;

/**
 * 重载是否管理员方法，实现对接口的管理员访问限制
 * Class AppAdminRequest
 * @package App\Http
 */
class AppAdminRequest extends AdminRequest
{
    protected function isAdmin()
    {
        if (!Auth::isLogin()) {
            return false;
        }
        $user = Auth::user();
        if (!$user instanceof User) {
            return false;
        }
        Log::info("user is admin:".$user->isAdmin());
        return  $user->isAdmin();
    }
}