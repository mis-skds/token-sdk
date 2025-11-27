<?php

namespace TokenManagement\SDK\Tests\Unit\Resources;

use TokenManagement\SDK\Tests\TestCase;
use TokenManagement\SDK\Resources\DisplayResource;
use TokenManagement\SDK\Http\Client;

class DisplayResourceTest extends TestCase
{
    private $client;
    private $displayResource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(Client::class);
        $this->displayResource = new DisplayResource($this->client);
    }

    public function testList()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/displays', [])
            ->willReturn([
                'data' => [
                    ['id' => 1, 'name' => 'Main Display'],
                    ['id' => 2, 'name' => 'Secondary Display']
                ]
            ]);

        $result = $this->displayResource->list();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGet()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/displays/1')
            ->willReturn([
                'data' => ['id' => 1, 'name' => 'Main Display']
            ]);

        $result = $this->displayResource->get(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreate()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/displays/save', ['name' => 'New Display', 'mlocation_id' => 1])
            ->willReturn([
                'data' => ['id' => 3, 'name' => 'New Display']
            ]);

        $result = $this->displayResource->create(['name' => 'New Display', 'mlocation_id' => 1]);
        $this->assertIsArray($result);
        $this->assertEquals(3, $result['id']);
    }

    public function testUpdate()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/displays/save', ['name' => 'Updated Display', 'id' => 1])
            ->willReturn([
                'data' => ['id' => 1, 'name' => 'Updated Display']
            ]);

        $result = $this->displayResource->update(1, ['name' => 'Updated Display']);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testDelete()
    {
        $this->client->expects($this->once())
            ->method('delete')
            ->with('/api/secured/displays/1')
            ->willReturn(['success' => true]);

        $result = $this->displayResource->delete(1);
        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
    }

    public function testGetData()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/common/displays/1/data')
            ->willReturn([
                'data' => [
                    'display' => ['id' => 1, 'name' => 'Main Display'],
                    'tokens' => [
                        ['token_number' => 'A001', 'status' => 3]
                    ]
                ]
            ]);

        $result = $this->displayResource->getData(1);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('display', $result);
        $this->assertArrayHasKey('tokens', $result);
    }
}
