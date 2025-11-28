<?php

namespace TokenManagement\SDK\Resources;

use TokenManagement\SDK\Http\Client;

/**
 * Location resource
 * 
 * @package TokenManagement\SDK\Resources
 */
class LocationResource
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list(array $filters = []): array
    {
        $response = $this->client->get('api/secured/locations', $filters);
        return $response['data'] ?? $response;
    }

    public function get(int $id): array
    {
        $response = $this->client->get("api/secured/locations/{$id}");
        return $response['data'] ?? $response;
    }

    public function create(array $data): array
    {
        $response = $this->client->post('api/secured/locations/save', $data);
        return $response['data'] ?? $response;
    }

    public function update(int $id, array $data): array
    {
        $data['id'] = $id;
        $response = $this->client->post('api/secured/locations/save', $data);
        return $response['data'] ?? $response;
    }

    public function delete(int $id): array
    {
        return $this->client->delete("api/secured/locations/{$id}");
    }
}
