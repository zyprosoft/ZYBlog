<?php


namespace App\Controller\Admin;


use ZYProSoft\Controller\AbstractController;
use App\Service\UserService;
use Hyperf\Di\Annotation\Inject;

class UserController extends AbstractController
{

    /**
     * @Inject
     * @var UserService
     */
    private $userService;

    public function login()
    {
       $params = $this->request->validate([
            'username' => 'string|max:20|exist:user,username|required',
            'password' => 'string|max:20|required',
        ]);
        $username = $params['username'];
        $password = $params['password'];
        $userInfo = $this->userService->login($username, $password);
        return $this->success($userInfo);
    }
}