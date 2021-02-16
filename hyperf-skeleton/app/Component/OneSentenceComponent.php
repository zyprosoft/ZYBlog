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

namespace App\Component;
use Hyperf\Utils\Arr;
use ZYProSoft\Component\BaseComponent;

/**
 * 获取一言的接口组件
 * Class OneSentenceComponent
 * @package App\Component
 */
class OneSentenceComponent extends BaseComponent
{
    protected array $options = [
        "base_uri" => "http://v1.hitokoto.cn",
        "timeout" => 3,
    ];

    const INTERFACE_GET_SENTENCE = "/";

    private array $typeList = ['a','b','c','d','e','f','h','i','j','k','l'];

    public function getOneSentence()
    {
        $type = rand(0,count($this->typeList)-1);
        $options['query'] = ['c' => Arr::get($this->typeList, $type)];
        $result = $this->get(self::INTERFACE_GET_SENTENCE, $options);
        if ($result->getStatusCode() != 200) {
            return  $this->success();
        }
        $result = json_decode($result->getBody()->getContents(), true);
        $sentence = Arr::get($result, 'hitokoto');
        if (!isset($sentence)) {
            return  $this->success();
        }
        $from = Arr::get($result, 'from');

        return $this->success([
            'sentence' => $sentence,
            'from' => $from
        ]);
    }
}