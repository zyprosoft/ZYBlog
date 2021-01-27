<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("用户密码错误")
     */
    const USER_ERROR_PASSWORD_WRONG = 20000;

    /**
     * @Message("用户无权进行此操作")
     */
    const USER_ACTION_NO_RIGHT = 20001;

    /**
     * @Message ("验证码已过期")
     */
    const SYSTEM_ERROR_CAPTCHA_EXPIRED = 30000;

    /**
     * @Message ("验证码输入错误")
     */
    const SYSTEM_ERROR_CAPTCHA_INVALIDATE = 30001;

    /**
     * @Message ("创建验证码文件夹出错")
     */
    const SYSTEM_ERROR_CAPTCHA_DIR_CREATE_FAIL = 30002;
}
