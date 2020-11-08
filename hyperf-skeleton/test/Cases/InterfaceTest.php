<?php


namespace HyperfTest\Cases;
use PHPUnit\Framework\TestCase;
use Qbhy\HyperfTesting\Client;

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
        $this->client->json('/', $body)->assertOk();
    }

    public function testLogin()
    {
        $interfaceName = 'admin.user.login';
        $params = [
            'username' => 'admin',
            'password' => 'admin123'
        ];
        $this->cgwRequest($interfaceName, $params);
    }
}