<?php

namespace TokenManagement\SDK\Resources;

use TokenManagement\SDK\Http\Client;

class ClientResource
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list(array $filters = []): array
    {
        $response = $this->client->get('api/secured/clients', $filters);
        return $response['data'] ?? $response;
    }

    public function get(int $id): array
    {
        $response = $this->client->get("api/secured/clients/{$id}");
        return $response['data'] ?? $response;
    }

    public function create(array $data): array
    {
        $response = $this->client->post('api/secured/clients', $data);
        return $response['data'] ?? $response;
    }

    public function update(int $id, array $data): array
    {
        $response = $this->client->put("api/secured/clients/{$id}", $data);
        return $response['data'] ?? $response;
    }

    public function delete(int $id): array
    {
        return $this->client->delete("api/secured/clients/{$id}");
    }
}
