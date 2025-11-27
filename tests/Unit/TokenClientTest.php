<?php

namespace TokenManagement\SDK\Tests\Unit;

use TokenManagement\SDK\Tests\TestCase;
use TokenManagement\SDK\TokenClient;
use TokenManagement\SDK\Auth\AuthService;
use TokenManagement\SDK\Resources\TokenResource;

class TokenClientTest extends TestCase
{
    public function testConstructorThrowsExceptionWithoutBaseUrl()
    {
        $this->expectException(\InvalidArgumentException::class);
        new TokenClient([]);
    }

    public function testConstructorWithValidConfig()
    {
        $client = new TokenClient(['base_url' => 'https://api.example.com']);
        $this->assertInstanceOf(TokenClient::class, $client);
    }

    public function testAuthAccessor()
    {
        $client = new TokenClient(['base_url' => 'https://api.example.com']);
        $this->assertInstanceOf(AuthService::class, $client->auth());
    }

    public function testTokensAccessor()
    {
        $client = new TokenClient(['base_url' => 'https://api.example.com']);
        $this->assertInstanceOf(TokenResource::class, $client->tokens());
    }

    public function testSetAndGetAccessToken()
    {
        $client = new TokenClient(['base_url' => 'https://api.example.com']);
        $client->setAccessToken('test-token');
        $this->assertEquals('test-token', $client->getAccessToken());
    }
}
