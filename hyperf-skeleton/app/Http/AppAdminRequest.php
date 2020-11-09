<?php


namespace App\Http;


use App\Model\User;
use ZYProSoft\Facade\Auth;
use ZYProSoft\Http\AdminRequest;

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
        return  $user->isAdmin();
    }
}