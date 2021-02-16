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
namespace App\Process;

use Hyperf\AsyncQueue\Process\ConsumerProcess;
use Hyperf\Process\Annotation\Process;

/**
 * @Process
 */
class AsyncQueueConsumer extends ConsumerProcess
{

}
