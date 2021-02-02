<?php


namespace App\Component;
use Hyperf\Utils\Arr;
use ZYProSoft\Component\BaseComponent;

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
        $result = json_decode($result->getBody(), true);
        $sentence = Arr::get($result, 'hitokoto');
        $from = Arr::get($result, 'from');

        return $this->success([
            'sentence' => $sentence,
            'from' => $from
        ]);
    }
}