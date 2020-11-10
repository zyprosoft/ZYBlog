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

    public function cgwRequest($interfaceName, $params, $token = null)
    {
        $body = [
            'token' => $token,
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
        $loginResponse = $this->cgwRequest($interfaceName, $params);
        return json_decode($loginResponse->getContent())->data->token;
    }

    public function testCreateArticle()
    {
        $token = $this->testLogin();

        Log::info("token:$token");

        $interfaceName = 'admin.article.create';
        $params = [
            'title' => '第一篇文章',
            'content' => '博客内容展示给大家',
            'tags' => ['随笔','演示'],
            'categoryId' => 1
        ];
        $this->cgwRequest($interfaceName, $params, $token);
    }

    public function testGetArticleList()
    {
        $interfaceName = 'common.article.list';
        $params = [
            'pageIndex' => 0,
            'pageSize' => 10
        ];
        return $this->cgwRequest($interfaceName, $params);
    }

    private function getOneArticle()
    {
        $response = $this->testGetArticleList();
        return json_decode($response->getContent(), true)['data']['data'][0];
    }

    private function getOneComment($articleId)
    {

    }

    public function testCreateComment()
    {
        $articleId = $this->getOneArticle()['article_id'];
        $content = "第一条测试评论";
        $interfaceName = 'common.article.addComment';
        $params = [
            'articleId' => $articleId,
            'content' => $content,
        ];
        return $this->cgwRequest($interfaceName, $params);
    }
}