<?php


namespace HyperfTest\Cases;
use PHPUnit\Framework\TestCase;
use Qbhy\HyperfTesting\Client;
use ZYProSoft\Log\Log;

class InterfaceTest extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = make(Client::class);
    }

    public function cgwRequest($interfaceName, $params)
    {
        $body = [
            'eventId' => time(),
            'timestamp' => time(),
            'version' => '1.0',
            'caller' => 'interfaceTest',
            'seqId' => rand(),
            'spanId' => 'test:step1',
            'interface' => [
                'interfaceName' => $interfaceName,
                'param' => $params
            ]
        ];
        return $this->client->json('/', $body)->assertOk()->assertStatus(200);
    }

    public function testLogin()
    {
        $interfaceName = 'admin.user.login';
        $params = [
            'username' => 'admin',
            'password' => 'admin123'
        ];
        $response = $this->cgwRequest($interfaceName, $params);
        return $response['data']['token'];
    }

    public function testCreateArticle()
    {
        $token = $this->testLogin();

        Log::info("token:$token");
        
        $interfaceName = 'admin.article.create';
        $params = [
            'token' => $token,
            'title' => '第一篇文章',
            'content' => '博客内容展示给大家',
            'tags' => ['随笔','演示'],
            'categoryId' => 0
        ];
        $this->cgwRequest($interfaceName, $params);
    }
}