<?php

namespace TokenManagement\SDK;

use TokenManagement\SDK\Auth\AuthService;
use TokenManagement\SDK\Resources\TokenResource;
use TokenManagement\SDK\Resources\LocationResource;
use TokenManagement\SDK\Resources\ServicePointResource;
use TokenManagement\SDK\Resources\TokenCategoryResource;
use TokenManagement\SDK\Resources\DisplayResource;
use TokenManagement\SDK\Resources\ClientResource;
use TokenManagement\SDK\Http\Client;

/**
 * Token Management API Client
 * 
 * Main entry point for the Token Management SDK
 * 
 * @package TokenManagement\SDK
 */
class TokenClient
{
    /** @var Client */
    private $httpClient;

    /** @var string|null */
    private $accessToken;

    /** @var AuthService */
    private $authService;

    /**
     * Create a new Token Management API client
     *
     * @param array $config Configuration options
     *   - base_url: string (required) Base URL of the API
     *   - client_id: string (optional) Client ID for machine-to-machine auth
     *   - client_secret: string (optional) Client Secret for machine-to-machine auth
     *   - timeout: int (optional) Request timeout in seconds (default: 30)
     *   - verify_ssl: bool (optional) Verify SSL certificates (default: true)
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['base_url'])) {
            throw new \InvalidArgumentException('base_url is required in configuration');
        }

        $this->httpClient = new Client($config);
        $this->authService = new AuthService($this->httpClient);
    }

    /**
     * Set the access token for authenticated requests
     *
     * @param string $token JWT access token
     * @return self
     */
    public function setAccessToken(string $token): self
    {
        $this->accessToken = $token;
        $this->httpClient->setAccessToken($token);
        return $this;
    }

    /**
     * Get the current access token
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Access authentication methods
     *
     * @return AuthService
     */
    public function auth(): AuthService
    {
        return $this->authService;
    }

    /**
     * Access token management methods
     *
     * @return TokenResource
     */
    public function tokens(): TokenResource
    {
        return new TokenResource($this->httpClient);
    }

    /**
     * Access location management methods
     *
     * @return LocationResource
     */
    public function locations(): LocationResource
    {
        return new LocationResource($this->httpClient);
    }

    /**
     * Access service point management methods
     *
     * @return ServicePointResource
     */
    public function servicePoints(): ServicePointResource
    {
        return new ServicePointResource($this->httpClient);
    }

    /**
     * Access token category management methods
     *
     * @return TokenCategoryResource
     */
    public function tokenCategories(): TokenCategoryResource
    {
        return new TokenCategoryResource($this->httpClient);
    }

    /**
     * Access display management methods
     *
     * @return DisplayResource
     */
    public function displays(): DisplayResource
    {
        return new DisplayResource($this->httpClient);
    }

    /**
     * Access client management methods
     *
     * @return ClientResource
     */
    public function clients(): ClientResource
    {
        return new ClientResource($this->httpClient);
    }
}
