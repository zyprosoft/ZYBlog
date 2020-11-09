<?php


namespace App\Http;


use App\Model\User;
use ZYProSoft\Facade\Auth;

class AppAdminRequest extends \ZYProSoft\Http\AdminRequest
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