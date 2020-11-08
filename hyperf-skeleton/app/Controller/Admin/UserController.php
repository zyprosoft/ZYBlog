<?php


namespace App\Controller\Admin;


use Hyperf\Contract\ContainerInterface;
use ZYProSoft\Controller\AbstractController;
use App\Service\UserService;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="/admin/user")
 * Class UserController
 * @package App\Controller\Admin
 */
class UserController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->userService = $container->get(UserService::class);
    }

    public function login()
    {
       $params = $this->validate([
            'username' => 'string|max:20|exist:user,username|required',
            'password' => 'string|max:20|required',
        ]);
        $username = $params['username'];
        $password = $params['password'];
        $userInfo = $this->userService->login($username, $password);
        return $this->success($userInfo);
    }
}