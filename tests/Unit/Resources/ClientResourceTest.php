<?php

namespace TokenManagement\SDK\Tests\Unit\Resources;

use TokenManagement\SDK\Tests\TestCase;
use TokenManagement\SDK\Resources\ClientResource;
use TokenManagement\SDK\Http\Client;

class ClientResourceTest extends TestCase
{
    private $client;
    private $clientResource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(Client::class);
        $this->clientResource = new ClientResource($this->client);
    }

    public function testList()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/clients', [])
            ->willReturn([
                'data' => [
                    ['id' => 1, 'name' => 'Kiosk 1'],
                    ['id' => 2, 'name' => 'Kiosk 2']
                ]
            ]);

        $result = $this->clientResource->list();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGet()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/clients/1')
            ->willReturn([
                'data' => ['id' => 1, 'name' => 'Kiosk 1']
            ]);

        $result = $this->clientResource->get(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreate()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/clients', ['name' => 'Kiosk 3', 'mlocation_id' => 1])
            ->willReturn([
                'data' => ['id' => 3, 'name' => 'Kiosk 3']
            ]);

        $result = $this->clientResource->create(['name' => 'Kiosk 3', 'mlocation_id' => 1]);
        $this->assertIsArray($result);
        $this->assertEquals(3, $result['id']);
    }

    public function testUpdate()
    {
        $this->client->expects($this->once())
            ->method('put')
            ->with('/api/secured/clients/1', ['name' => 'Updated Kiosk'])
            ->willReturn([
                'data' => ['id' => 1, 'name' => 'Updated Kiosk']
            ]);

        $result = $this->clientResource->update(1, ['name' => 'Updated Kiosk']);
        $this->assertIsArray($result);
        $this->assertEquals('Updated Kiosk', $result['name']);
    }

    public function testDelete()
    {
        $this->client->expects($this->once())
            ->method('delete')
            ->with('/api/secured/clients/1')
            ->willReturn(['success' => true]);

        $result = $this->clientResource->delete(1);
        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
    }
}
