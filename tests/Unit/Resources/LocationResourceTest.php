<?php

namespace TokenManagement\SDK\Tests\Unit\Resources;

use TokenManagement\SDK\Tests\TestCase;
use TokenManagement\SDK\Resources\LocationResource;
use TokenManagement\SDK\Http\Client;

class LocationResourceTest extends TestCase
{
    private $client;
    private $locationResource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(Client::class);
        $this->locationResource = new LocationResource($this->client);
    }

    public function testList()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/locations', [])
            ->willReturn([
                'data' => [
                    ['id' => 1, 'name' => 'Main Branch'],
                    ['id' => 2, 'name' => 'Downtown Branch']
                ]
            ]);

        $result = $this->locationResource->list();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGet()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/locations/1')
            ->willReturn([
                'data' => ['id' => 1, 'name' => 'Main Branch']
            ]);

        $result = $this->locationResource->get(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreate()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/locations/save', ['name' => 'New Branch', 'city' => 'NYC'])
            ->willReturn([
                'data' => ['id' => 3, 'name' => 'New Branch', 'city' => 'NYC']
            ]);

        $result = $this->locationResource->create(['name' => 'New Branch', 'city' => 'NYC']);
        $this->assertIsArray($result);
        $this->assertEquals(3, $result['id']);
    }

    public function testUpdate()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/locations/save', ['name' => 'Updated Branch', 'id' => 1])
            ->willReturn([
                'data' => ['id' => 1, 'name' => 'Updated Branch']
            ]);

        $result = $this->locationResource->update(1, ['name' => 'Updated Branch']);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testDelete()
    {
        $this->client->expects($this->once())
            ->method('delete')
            ->with('/api/secured/locations/1')
            ->willReturn(['success' => true]);

        $result = $this->locationResource->delete(1);
        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
    }
}
