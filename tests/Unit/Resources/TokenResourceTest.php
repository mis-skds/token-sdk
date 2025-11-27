<?php

namespace TokenManagement\SDK\Tests\Unit\Resources;

use TokenManagement\SDK\Tests\TestCase;
use TokenManagement\SDK\Resources\TokenResource;
use TokenManagement\SDK\Http\Client;
use TokenManagement\SDK\Models\Token;

class TokenResourceTest extends TestCase
{
    private $client;
    private $tokenResource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(Client::class);
        $this->tokenResource = new TokenResource($this->client);
    }

    public function testList()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/tokens', ['mlocation_id' => 1])
            ->willReturn([
                'data' => [
                    ['id' => 1, 'token_number' => 'A001'],
                    ['id' => 2, 'token_number' => 'A002']
                ]
            ]);

        $result = $this->tokenResource->list(['mlocation_id' => 1]);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGet()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/tokens/1')
            ->willReturn([
                'data' => [
                    'id' => 1,
                    'token_number' => 'A001',
                    'status' => 1
                ]
            ]);

        $token = $this->tokenResource->get(1);
        $this->assertInstanceOf(Token::class, $token);
    }

    public function testIssue()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/tokens/issue-token', [
                'mlocation_id' => 1,
                'mservicepoint_id' => 2,
                'customer_name' => 'John Doe'
            ])
            ->willReturn([
                'data' => [
                    'id' => 1,
                    'token_number' => 'A001',
                    'status' => 1
                ]
            ]);

        $token = $this->tokenResource->issue([
            'mlocation_id' => 1,
            'mservicepoint_id' => 2,
            'customer_name' => 'John Doe'
        ]);

        $this->assertInstanceOf(Token::class, $token);
    }

    public function testFindNext()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/tokens/find-next-token', ['mlocation_id' => 1])
            ->willReturn([
                'data' => ['next_token' => 'A005']
            ]);

        $result = $this->tokenResource->findNext(1);
        $this->assertIsArray($result);
        $this->assertEquals('A005', $result['next_token']);
    }

    public function testCallNext()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/tokens/call-next', [
                'mlocation_id' => 1,
                'mservicepoint_id' => 2
            ])
            ->willReturn([
                'data' => [
                    'id' => 1,
                    'token_number' => 'A001',
                    'status' => 2
                ]
            ]);

        $token = $this->tokenResource->callNext(1, 2);
        $this->assertInstanceOf(Token::class, $token);
    }

    public function testCallById()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/tokens/call-by-id/5', [
                'mlocation_id' => 1,
                'mservicepoint_id' => 2
            ])
            ->willReturn([
                'data' => [
                    'id' => 5,
                    'token_number' => 'A005',
                    'status' => 2
                ]
            ]);

        $token = $this->tokenResource->callById(5, 1, 2);
        $this->assertInstanceOf(Token::class, $token);
    }

    public function testSkip()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/tokens/skip-by-id/5', [
                'mlocation_id' => 1,
                'mservicepoint_id' => 2
            ])
            ->willReturn([
                'data' => [
                    'id' => 5,
                    'token_number' => 'A005',
                    'status' => 5
                ]
            ]);

        $token = $this->tokenResource->skip(5, 1, 2);
        $this->assertInstanceOf(Token::class, $token);
    }

    public function testComplete()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/tokens/complete-by-id/5', [
                'mlocation_id' => 1,
                'mservicepoint_id' => 2
            ])
            ->willReturn([
                'data' => [
                    'id' => 5,
                    'token_number' => 'A005',
                    'status' => 4
                ]
            ]);

        $token = $this->tokenResource->complete(5, 1, 2);
        $this->assertInstanceOf(Token::class, $token);
    }

    public function testCurrentlyServing()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/tokens/currently-serving', ['mlocation_id' => 1])
            ->willReturn([
                'data' => [
                    ['token_number' => 'A001', 'servicepoint_name' => 'Counter 1'],
                    ['token_number' => 'A002', 'servicepoint_name' => 'Counter 2']
                ]
            ]);

        $result = $this->tokenResource->currentlyServing(1);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testUpdate()
    {
        $this->client->expects($this->once())
            ->method('put')
            ->with('/api/secured/tokens/1', ['customer_name' => 'Jane Doe'])
            ->willReturn([
                'data' => [
                    'id' => 1,
                    'token_number' => 'A001',
                    'customer_name' => 'Jane Doe'
                ]
            ]);

        $token = $this->tokenResource->update(1, ['customer_name' => 'Jane Doe']);
        $this->assertInstanceOf(Token::class, $token);
    }

    public function testDelete()
    {
        $this->client->expects($this->once())
            ->method('delete')
            ->with('/api/secured/tokens/1')
            ->willReturn(['success' => true]);

        $result = $this->tokenResource->delete(1);
        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
    }
}
