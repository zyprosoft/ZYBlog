<?php

declare (strict_types=1);
/**
 * This file is part of qbhy/hyperf-auth.
 *
 * @link     https://github.com/qbhy/hyperf-auth
 * @document https://github.com/qbhy/hyperf-auth/blob/master/README.md
 * @contact  qbhy0715@qq.com
 * @license  https://github.com/qbhy/hyperf-auth/blob/master/LICENSE
 */
namespace Qbhy\HyperfAuth;

use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Qbhy\HyperfAuth\Annotation\Auth;
use Qbhy\HyperfAuth\Exception\UnauthorizedException;
/**
 * @Aspect
 */
class AuthAspect extends AbstractAspect
{
    use \Hyperf\Di\Aop\ProxyTrait;
    use \Hyperf\Di\Aop\PropertyHandlerTrait;
    function __construct()
    {
        if (method_exists(parent::class, '__construct')) {
            parent::__construct(...func_get_args());
        }
        self::__handlePropertyHandler(__CLASS__);
    }
    public $annotations = [Auth::class];
    /**
     * @Inject
     * @var AuthManager
     */
    protected $auth;
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $annotation = $proceedingJoinPoint->getAnnotationMetadata();
        /** @var Auth $authAnnotation */
        $authAnnotation = $annotation->class[Auth::class] ?? $annotation->method[Auth::class];
        $guards = is_array($authAnnotation->value) ? $authAnnotation->value : [$authAnnotation->value];
        foreach ($guards as $name) {
            $guard = $this->auth->guard($name);
            if (!$guard->user() instanceof Authenticatable) {
                throw new UnauthorizedException("Without authorization from {$guard->getName()} guard", $guard);
            }
        }
        return $proceedingJoinPoint->process();
    }
}