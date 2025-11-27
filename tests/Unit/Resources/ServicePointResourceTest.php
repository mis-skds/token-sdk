<?php

namespace TokenManagement\SDK\Tests\Unit\Resources;

use TokenManagement\SDK\Tests\TestCase;
use TokenManagement\SDK\Resources\ServicePointResource;
use TokenManagement\SDK\Http\Client;

class ServicePointResourceTest extends TestCase
{
    private $client;
    private $servicePointResource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(Client::class);
        $this->servicePointResource = new ServicePointResource($this->client);
    }

    public function testList()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/service-points', ['mlocation_id' => 1])
            ->willReturn([
                'data' => [
                    ['id' => 1, 'name' => 'Counter 1'],
                    ['id' => 2, 'name' => 'Counter 2']
                ]
            ]);

        $result = $this->servicePointResource->list(['mlocation_id' => 1]);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGet()
    {
        $this->client->expects($this->once())
            ->method('get')
            ->with('/api/secured/service-points/1')
            ->willReturn([
                'data' => ['id' => 1, 'name' => 'Counter 1']
            ]);

        $result = $this->servicePointResource->get(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreate()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/service-points/save', ['name' => 'Counter 3', 'mlocation_id' => 1])
            ->willReturn([
                'data' => ['id' => 3, 'name' => 'Counter 3']
            ]);

        $result = $this->servicePointResource->create(['name' => 'Counter 3', 'mlocation_id' => 1]);
        $this->assertIsArray($result);
        $this->assertEquals(3, $result['id']);
    }

    public function testUpdate()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/secured/service-points/save', ['name' => 'Updated Counter', 'id' => 1])
            ->willReturn([
                'data' => ['id' => 1, 'name' => 'Updated Counter']
            ]);

        $result = $this->servicePointResource->update(1, ['name' => 'Updated Counter']);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testDelete()
    {
        $this->client->expects($this->once())
            ->method('delete')
            ->with('/api/secured/service-points/1')
            ->willReturn(['success' => true]);

        $result = $this->servicePointResource->delete(1);
        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
    }
}
