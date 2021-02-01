<?php


namespace App\Component;
use Hyperf\Utils\Arr;
use ZYProSoft\Component\BaseComponent;

class OneSentenceComponent extends BaseComponent
{
    protected array $options = [
        "base_uri" => "http://api.youngam.cn/",
        "timeout" => 3,
    ];

    const INTERFACE_GET_SENTENCE = "api/one.php";

    public function getOneSentence()
    {
        $result = $this->get(self::INTERFACE_GET_SENTENCE);
        if ($result->getStatusCode() != 200) {
            return  $this->success();
        }
        $sentence = Arr::get(json_decode($result->getBody(), true), 'data.text');

        return $this->success($sentence);
    }
}