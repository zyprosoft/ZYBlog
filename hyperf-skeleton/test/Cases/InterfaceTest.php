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
            'interface' => [
                'name' => $interfaceName,
                'param' => $params
            ]
        ];
        return $this->client->json('/', $body)->assertOk();
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
        $this->cgwRequest($interfaceName, $params, $token)->assertOk();
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
        return json_decode($response->getContent(), true)['data'][0];
    }

    public function testCreateComment()
    {
        $token = $this->testLogin();

        $articleId = $this->getOneArticle()['article_id'];
        $content = "第一条测试评论";
        $interfaceName = 'common.article.addComment';
        $params = [
            'articleId' => $articleId,
            'content' => $content,
        ];
        return $this->cgwRequest($interfaceName, $params, $token)->assertOk();
    }

    public function testGetArticleDetail()
    {
        $articleId = $this->getOneArticle()['article_id'];
        $interfaceName = 'common.article.detail';
        $params = [
            'articleId' => $articleId,
        ];
        return $this->cgwRequest($interfaceName, $params)->assertOk();
    }

    public function testCommentList()
    {
        $articleId = $this->getOneArticle()['article_id'];
        $interfaceName = 'common.article.commentList';
        $params = [
            'articleId' => $articleId,
        ];
        return $this->cgwRequest($interfaceName, $params)->assertOk();
    }

    private function getOneComment()
    {
        $response = $this->testCommentList();
        return json_decode($response->getContent(), true)['data'][0];
    }

    public function testReplyComment()
    {
        $token = $this->testLogin();

        $commentId = $this->getOneComment()['comment_id'];
        $content = "第一条回复别人的评论";
        $interfaceName = 'common.article.replyComment';
        $params = [
            'commentId' => $commentId,
            'content' => $content,
        ];
        $this->cgwRequest($interfaceName, $params, $token)->assertOk();
    }

    public function testCommentDetail()
    {
        $commentId = $this->getOneComment()['comment_id'];
        $interfaceName = 'common.comment.detail';
        $params = [
            'commentId' => $commentId,
        ];
        $this->cgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetAllCategory()
    {
        $interfaceName = 'common.category.getAll';
        $params = [
        ];
        $this->cgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetArticleListByTagId()
    {
        $interfaceName = 'common.article.getListByTagId';
        $params = [
            'pageIndex' => 0,
            'pageSize' => 10,
            'tagId' => 43
        ];
        $this->cgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetArticleListByDate()
    {
        $interfaceName = 'common.article.getListByDate';
        $params = [
            'pageIndex' => 0,
            'pageSize' => 10,
            'date' => '2021-1-01'
        ];
        $this->cgwRequest($interfaceName, $params)->assertOk();
    }
}