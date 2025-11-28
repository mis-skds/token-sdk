<?php

namespace TokenManagement\SDK\Tests\Unit;

use TokenManagement\SDK\Tests\TestCase;
use TokenManagement\SDK\TokenClient;
use TokenManagement\SDK\Http\Client;

class HealthCheckTest extends TestCase
{
    public function testHealthCheckSuccess()
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('get')
            ->with('/api/health')
            ->willReturn([
                'status' => 'ok',
                'timestamp' => '2025-11-27T19:45:00Z',
                'version' => '1.0.0'
            ]);

        $tokenClient = new TokenClient(['base_url' => 'https://api.example.com']);
        
        // Use reflection to inject mock client
        $reflection = new \ReflectionClass($tokenClient);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($tokenClient, $mockClient);

        $health = $tokenClient->health();
        
        $this->assertIsArray($health);
        $this->assertEquals('ok', $health['status']);
    }

    public function testHealthCheckFailure()
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('get')
            ->with('/api/health')
            ->willThrowException(new \Exception('Connection refused'));

        $tokenClient = new TokenClient(['base_url' => 'https://api.example.com']);
        
        // Use reflection to inject mock client
        $reflection = new \ReflectionClass($tokenClient);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($tokenClient, $mockClient);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Health check failed: Connection refused');
        
        $tokenClient->health();
    }
}
