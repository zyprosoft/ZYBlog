<?php

/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  泽湾普罗信息技术有限公司(ZYProSoft)
 * @license  GPL
 */

namespace App\Http;


use App\Model\User;
use ZYProSoft\Facade\Auth;
use ZYProSoft\Http\AdminRequest;
use ZYProSoft\Log\Log;

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