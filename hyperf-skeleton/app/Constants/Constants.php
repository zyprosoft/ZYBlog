<?php


namespace App\Constants;


use Hyperf\Constants\AbstractConstants;

class Constants extends AbstractConstants
{
    const USER_ROLE_ADMIN = 1;

    const UPLOAD_SYSTEM_TYPE_LOCAL = 'local';

    const UPLOAD_SYSTEM_TYPE_QINIU = 'qiniu';
}