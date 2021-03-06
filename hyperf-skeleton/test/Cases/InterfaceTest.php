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

namespace HyperfTest\Cases;
use App\Component\OneSentenceComponent;
use App\Service\CommonService;
use Hyperf\Utils\ApplicationContext;
use PHPUnit\Framework\TestCase;
use Qbhy\HyperfTesting\Client;
use ZYProSoft\Log\Log;
use ZYProSoft\Service\CaptchaService;
use ZYProSoft\Service\LogService;
use ZYProSoft\Entry\EmailEntry;
use ZYProSoft\Entry\EmailAddressEntry;
use ZYProSoft\Service\EmailService;

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

    public function zgwRequest($interfaceName, $params, $token = null)
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
        $loginResponse = $this->zgwRequest($interfaceName, $params);
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
        $this->zgwRequest($interfaceName, $params, $token)->assertOk();
    }

    public function testGetArticleList()
    {
        $interfaceName = 'common.article.list';
        $params = [
            'pageIndex' => 0,
            'pageSize' => 10
        ];
        return $this->zgwRequest($interfaceName, $params);
    }

    private function getOneArticle()
    {
        $response = $this->testGetArticleList();
        return json_decode($response->getContent(), true)['data']['list'][0];
    }

    public function testCreateComment()
    {
        $token = $this->testLogin();

        $articleId = $this->getOneArticle()['article_id'];
        $content = "第一条测试评论";
        $interfaceName = 'common.comment.create';
        $params = [
            'articleId' => $articleId,
            'content' => $content,
            'nickname' => 'mike5262705',
            'email' => '1003081775@qq.com',
            'site' => 'http://www.github.com/zyprosoft'
        ];
        return $this->zgwRequest($interfaceName, $params, $token)->assertOk();
    }

    public function testGetArticleDetail()
    {
        $articleId = $this->getOneArticle()['article_id'];
        $interfaceName = 'common.article.detail';
        $params = [
            'articleId' => $articleId,
        ];
        return $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testCommentList()
    {
        $articleId = $this->getOneArticle()['article_id'];
        $interfaceName = 'common.comment.list';
        $params = [
            'articleId' => $articleId,
        ];
        return $this->zgwRequest($interfaceName, $params)->assertOk();
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
        $interfaceName = 'common.comment.reply';
        $params = [
            'commentId' => $commentId,
            'content' => $content,
        ];
        $this->zgwRequest($interfaceName, $params, $token)->assertOk();
    }

    public function testCommentDetail()
    {
        $commentId = $this->getOneComment()['comment_id'];
        $interfaceName = 'common.comment.detail';
        $params = [
            'commentId' => $commentId,
        ];
        $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetAllCategory()
    {
        $interfaceName = 'common.category.getAll';
        $params = [
        ];
        $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetArticleListByTagId()
    {
        $interfaceName = 'common.article.getListByTagId';
        $params = [
            'pageIndex' => 0,
            'pageSize' => 10,
            'tagId' => 11
        ];
        $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetArticleListByDate()
    {
        $interfaceName = 'common.article.getListByDate';
        $params = [
            'pageIndex' => 0,
            'pageSize' => 10,
            'date' => '2021-01'
        ];
        $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetArticleListByRecentComment()
    {
        $interfaceName = 'common.article.getListByRecentComment';
        $params = [
            'pageIndex' => 0,
            'pageSize' => 10,
        ];
        $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetAllArchiveDate()
    {
        $interfaceName = 'common.category.getAllArchiveDate';
        $params = [
        ];
        $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetHotTags()
    {
        $interfaceName = 'common.tag.getHotTags';
        $params = [
        ];
        $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetArchiveDateList()
    {
        $interfaceName = 'common.article.getArchiveDateList';
        $params = [
        ];
        $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testGetCaptcha()
    {
        $interfaceName = 'common.captcha.get';
        $params = [
        ];
        $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testClearCaptcha()
    {
        $service = ApplicationContext::getContainer()->get(CaptchaService::class);
        $service->clearExpireCaptcha();
    }

    public function testClearLog()
    {
        $service = ApplicationContext::getContainer()->get(LogService::class);
        $service->clearExpireLog();
    }

    public function testCommentListWithArticleId()
    {
        $service = ApplicationContext::getContainer()->get(CommonService::class);
        $service->clearSystemCache();
        $articleId = 1;
        $interfaceName = 'common.comment.list';
        $params = [
            'articleId' => $articleId,
            'pageIndex' => 0,
            'pageSize' => 10
        ];
        return $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testCommentDetailWithCommentId()
    {
        $commentId = 1;
        $interfaceName = 'common.comment.detail';
        $params = [
            'commentId' => $commentId,
        ];
        $this->zgwRequest($interfaceName, $params)->assertOk();
    }

    public function testSendEmail()
    {
        $email = new EmailEntry();
        $email->from = new EmailAddressEntry('1003081775@qq.com', 'zyprosoft');
        $email->receivers = [
            new EmailAddressEntry('1003081775@qq.com', '冰泪')
        ];
        $email->replyTo = new EmailAddressEntry('1003081775@qq.com', '冰泪');
        $email->subject = "测试邮件发送";
        $email->body = "测试邮件发送内容";
        $service = ApplicationContext::getContainer()->get(EmailService::class);
        $service->sendEmail($email);
    }

    public function testGetOneSentence()
    {
        $component = make(OneSentenceComponent::class);
        $result = $component->getOneSentence();
        echo json_encode($result);
    }
}