<?php
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
    private $userService;

    public function login()
    {
       $this->validate([
            'username' => 'string|max:20|exists:user,username|required',
            'password' => 'string|max:20|required',
           'captcha.key' => 'string|required|min:1',
           'captcha.code' => 'string|required|min:1'
        ]);
        //先校验验证码是否正确
        $key = $this->request->param('captcha.key');
        $code = $this->request->param('captcha.code');
        $this->captchaService->validate($key, $code);
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        $userInfo = $this->userService->login($username, $password);
        return $this->success($userInfo);
    }

    public function logout(AppAdminRequest $request)
    {
        $this->userService->logout();
        return $this->success();
    }
}