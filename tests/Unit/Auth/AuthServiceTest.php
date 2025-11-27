<?php

namespace TokenManagement\SDK\Tests\Unit\Auth;

use TokenManagement\SDK\Tests\TestCase;
use TokenManagement\SDK\Auth\AuthService;
use TokenManagement\SDK\Http\Client;
use TokenManagement\SDK\Models\AuthResponse;

class AuthServiceTest extends TestCase
{
    private $client;
    private $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->createMock(Client::class);
        $this->authService = new AuthService($this->client);
    }

    public function testSignUp()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/auth/signup', [
                'first_name' => 'John',
                'username' => 'john@example.com',
                'password' => 'secret'
            ])
            ->willReturn([
                'data' => [
                    'tokens' => [
                        'accessToken' => 'access-token',
                        'refreshToken' => 'refresh-token'
                    ]
                ]
            ]);

        $response = $this->authService->signUp('John', 'john@example.com', 'secret');

        $this->assertInstanceOf(AuthResponse::class, $response);
        $this->assertEquals('access-token', $response->getAccessToken());
    }

    public function testSignIn()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/auth/signin', [
                'username' => 'john@example.com',
                'password' => 'secret'
            ])
            ->willReturn([
                'data' => [
                    'tokens' => [
                        'accessToken' => 'access-token',
                        'refreshToken' => 'refresh-token'
                    ]
                ]
            ]);

        $response = $this->authService->signIn('john@example.com', 'secret');

        $this->assertInstanceOf(AuthResponse::class, $response);
        $this->assertEquals('access-token', $response->getAccessToken());
    }

    public function testSignInWithGoogle()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/auth/signin-with-google-v1', [
                'code' => 'google-code'
            ])
            ->willReturn([
                'data' => [
                    'accessToken' => 'access-token',
                    'refreshToken' => 'refresh-token'
                ]
            ]);

        $response = $this->authService->signInWithGoogle('google-code');

        $this->assertInstanceOf(AuthResponse::class, $response);
    }

    public function testRefreshToken()
    {
        $this->client->expects($this->once())
            ->method('post')
            ->with('/api/auth/refresh-token', [
                'refreshToken' => 'old-refresh-token'
            ])
            ->willReturn([
                'data' => [
                    'tokens' => [
                        'accessToken' => 'new-access-token',
                        'refreshToken' => 'new-refresh-token'
                    ]
                ]
            ]);

        $response = $this->authService->refreshToken('old-refresh-token');

        $this->assertInstanceOf(AuthResponse::class, $response);
        $this->assertEquals('new-access-token', $response->getAccessToken());
    }
}
