<?php

namespace TokenManagement\SDK\Models;

/**
 * Authentication response model
 * 
 * @package TokenManagement\SDK\Models
 */
class AuthResponse
{
    /** @var array */
    private $user;

    /** @var array */
    private $permissions;

    /** @var string */
    private $accessToken;

    /** @var string */
    private $refreshToken;

    /**
     * Create a new auth response
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->user = $data['user'] ?? [];
        $this->permissions = $data['permissions'] ?? [];
        $this->accessToken = $data['tokens']['accessToken'] ?? '';
        $this->refreshToken = $data['tokens']['refreshToken'] ?? '';
    }

    /**
     * Get user data
     *
     * @return array
     */
    public function getUser(): array
    {
        return $this->user;
    }

    /**
     * Get user ID
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user['id'] ?? null;
    }

    /**
     * Get username
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->user['username'] ?? null;
    }

    /**
     * Get permissions
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * Get access token
     *
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Get refresh token
     *
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
