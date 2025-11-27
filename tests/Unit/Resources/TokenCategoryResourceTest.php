<?php

namespace TokenManagement\SDK\Tests\Unit\Resources;

use TokenManagement\SDK\Tests\TestCase;
use TokenManagement\SDK\Resources\TokenCategoryResource;
use TokenManagement\SDK\Http\Client;

class TokenCategoryResourceTest extends TestCase
{
    private $client;
    private $tokenCategoryResource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(Client::class);
        $this->tokenCategoryResource = new TokenCategoryResource($this->client);
    }

    public function testList()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/token-categories', [])
            ->willReturn([
                'data' => [
                    ['id' => 1, 'name' => 'General', 'prefix' => 'G'],
                    ['id' => 2, 'name' => 'VIP', 'prefix' => 'V']
                ]
            ]);

        $result = $this->tokenCategoryResource->list();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGet()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/token-categories/1')
            ->willReturn([
                'data' => ['id' => 1, 'name' => 'General', 'prefix' => 'G']
            ]);

        $result = $this->tokenCategoryResource->get(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreate()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/token-categories/save', ['name' => 'Priority', 'prefix' => 'P'])
            ->willReturn([
                'data' => ['id' => 3, 'name' => 'Priority', 'prefix' => 'P']
            ]);

        $result = $this->tokenCategoryResource->create(['name' => 'Priority', 'prefix' => 'P']);
        $this->assertIsArray($result);
        $this->assertEquals(3, $result['id']);
    }

    public function testUpdate()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/token-categories/save', ['name' => 'Updated Category', 'id' => 1])
            ->willReturn([
                'data' => ['id' => 1, 'name' => 'Updated Category']
            ]);

        $result = $this->tokenCategoryResource->update(1, ['name' => 'Updated Category']);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testDelete()
    {
        $this->client->expects($this->once())
            ->method('delete')
            ->with('/api/secured/token-categories/1')
            ->willReturn(['success' => true]);

        $result = $this->tokenCategoryResource->delete(1);
        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
    }
}
