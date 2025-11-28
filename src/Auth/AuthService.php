<?php

namespace TokenManagement\SDK\Auth;

use TokenManagement\SDK\Http\Client;
use TokenManagement\SDK\Models\AuthResponse;

/**
 * Authentication service
 * 
 * Handles user authentication and token management
 * 
 * @package TokenManagement\SDK\Auth
 */
class AuthService
{
    /** @var Client */
    private $client;

    /**
     * Create a new auth service
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Sign up a new user
     *
     * @param string $firstName
     * @param string $username Email address
     * @param string $password
     * @return AuthResponse
     */
    public function signUp(string $firstName, string $username, string $password): AuthResponse
    {
        $response = $this->client->post('api/auth/signup', [
            'first_name' => $firstName,
            'username' => $username,
            'password' => $password
        ]);

        return new AuthResponse($response['data'] ?? $response);
    }

    /**
     * Sign in an existing user
     *
     * @param string $username Email address
     * @param string $password
     * @return AuthResponse
     */
    public function signIn(string $username, string $password): AuthResponse
    {
        $response = $this->client->post('api/auth/signin', [
            'username' => $username,
            'password' => $password
        ]);

        return new AuthResponse($response['data'] ?? $response);
    }

    /**
     * Sign in with Google OAuth
     *
     * @param string $code Google authorization code
     * @param int|null $roleId Optional role ID
     * @return AuthResponse
     */
    public function signInWithGoogle(string $code, ?int $roleId = null): AuthResponse
    {
        $data = ['code' => $code];
        
        if ($roleId !== null) {
            $data['mrole_id'] = $roleId;
        }

        $response = $this->client->post('api/auth/signInWithGoogleV1', $data);

        return new AuthResponse($response['data'] ?? $response);
    }

    /**
     * Refresh access token using refresh token
     *
     * @param string $refreshToken
     * @return AuthResponse
     */
    public function refreshToken(string $refreshToken): AuthResponse
    {
        $response = $this->client->post('api/auth/refresh-token', [
            'refreshToken' => $refreshToken
        ]);

        return new AuthResponse($response['data'] ?? $response);
    }
}
