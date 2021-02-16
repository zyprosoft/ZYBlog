<?php
/**
 * This file is part of ZYProSoft/ZYBlog.
 *
 * @link     http://zyprosoft.lulinggushi.com
 * @document http://zyprosoft.lulinggushi.com
 * @contact  1003081775@qq.com
 * @Company  ZYProSoft
 * @license  GPL
 */

declare(strict_types=1);

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
     * @Message("创建评论用户失败")
     */
    const COMMENT_USER_CREATE_NEW_FAIL = 20002;
}
