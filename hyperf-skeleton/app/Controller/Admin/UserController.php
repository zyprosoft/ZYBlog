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

declare(strict_types=1);

namespace App\Controller\Admin;


use App\Http\AppAdminRequest;
use ZYProSoft\Controller\AbstractController;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="/admin/user")
 * Class UserController
 * @package App\Controller\Admin
 */
class UserController extends AbstractController
{
    /**
     * @Inject
     * @var UserService
     */
    private UserService $userService;

    /**
     * 博主登陆接口
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login()
    {
        //先校验验证码是否正确
        $this->validateCaptcha();
        $this->validate([
           'username' => 'string|max:20|exists:user,username|required',
           'password' => 'string|max:20|required',
        ]);
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        $userInfo = $this->userService->login($username, $password);
        return $this->success($userInfo);
    }

    /**
     * 博主登出接口
     * @param AppAdminRequest $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function logout(AppAdminRequest $request)
    {
        $this->userService->logout();
        return $this->success();
    }
}