<?php

namespace TokenManagement\SDK\Resources;

use TokenManagement\SDK\Http\Client;
use TokenManagement\SDK\Models\Token;

/**
 * Token resource
 * 
 * Handles all token-related API operations
 * 
 * @package TokenManagement\SDK\Resources
 */
class TokenResource
{
    /** @var Client */
    private $client;

    /**
     * Create a new token resource
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * List all tokens with optional filters
     *
     * @param array $filters Optional filters (mlocation_id, status, page, pageLength, etc.)
     * @return array
     */
    public function list(array $filters = []): array
    {
        $response = $this->client->get('api/secured/tokens', $filters);
        return $response['data'] ?? $response;
    }

    /**
     * Get a specific token by ID
     *
     * @param int $id
     * @return Token
     */
    public function get(int $id): Token
    {
        $response = $this->client->get("api/secured/tokens/{$id}");
        return new Token($response['data'] ?? $response);
    }

    /**
     * Issue a new token
     *
     * @param array $data Token data (mlocation_id required)
     * @return Token
     */
    public function issue(array $data): Token
    {
        $response = $this->client->post('api/secured/tokens/issue', $data);
        return new Token($response['data'] ?? $response);
    }

    /**
     * Find the next token number that will be issued
     *
     * @param int $locationId
     * @return array
     */
    public function findNext(int $locationId): array
    {
        $response = $this->client->post('api/secured/tokens/find-next-token', [
            'mlocation_id' => $locationId
        ]);
        return $response['data'] ?? $response;
    }

    /**
     * Call the next waiting token
     *
     * @param int $locationId
     * @param int $servicePointId
     * @return Token
     */
    public function callNext(int $locationId, int $servicePointId): Token
    {
        $response = $this->client->post('api/secured/tokens/call-next', [
            'mlocation_id' => $locationId,
            'mservicepoint_id' => $servicePointId
        ]);
        return new Token($response['data'] ?? $response);
    }

    /**
     * Call a specific token by ID
     *
     * @param int $tokenId
     * @param int $locationId
     * @param int $servicePointId
     * @return Token
     */
    public function callById(int $tokenId, int $locationId, int $servicePointId): Token
    {
        $response = $this->client->post("api/secured/tokens/{$tokenId}/call", [
            'mlocation_id' => $locationId,
            'mservicepoint_id' => $servicePointId
        ]);
        return new Token($response['data'] ?? $response);
    }

    /**
     * Skip a token by ID
     *
     * @param int $tokenId
     * @param int $locationId
     * @param int $servicePointId
     * @return Token
     */
    public function skip(int $tokenId, int $locationId, int $servicePointId): Token
    {
        $response = $this->client->post("api/secured/tokens/{$tokenId}/skip", [
            'mlocation_id' => $locationId,
            'mservicepoint_id' => $servicePointId
        ]);
        return new Token($response['data'] ?? $response);
    }

    /**
     * Complete a token by ID
     *
     * @param int $tokenId
     * @param int $locationId
     * @param int $servicePointId
     * @return Token
     */
    public function complete(int $tokenId, int $locationId, int $servicePointId): Token
    {
        $response = $this->client->post("api/secured/tokens/{$tokenId}/complete", [
            'mlocation_id' => $locationId,
            'mservicepoint_id' => $servicePointId
        ]);
        return new Token($response['data'] ?? $response);
    }

    /**
     * Get currently serving tokens
     *
     * @param int $locationId
     * @return array
     */
    public function currentlyServing(int $locationId): array
    {
        $response = $this->client->get('api/secured/tokens/currently-serving', [
            'mlocation_id' => $locationId
        ]);
        return $response['data'] ?? $response;
    }

    /**
     * Update a token
     *
     * @param int $id
     * @param array $data
     * @return Token
     */
    public function update(int $id, array $data): Token
    {
        $response = $this->client->put("api/secured/tokens/{$id}", $data);
        return new Token($response['data'] ?? $response);
    }

    /**
     * Delete a token
     *
     * @param int $id
     * @return array
     */
    public function delete(int $id): array
    {
        return $this->client->delete("api/secured/tokens/{$id}");
    }
}
