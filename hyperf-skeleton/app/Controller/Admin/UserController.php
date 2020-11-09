<?php


namespace App\Controller\Admin;


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
//       $params = $this->validate([
//            'username' => 'string|max:20|exist:user,username|required',
//            'password' => 'string|max:20|required',
//        ]);
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        $userInfo = $this->userService->login($username, $password);
        return $this->success($userInfo);
    }
}