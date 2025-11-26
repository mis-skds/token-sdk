<?php

namespace TokenManagement\SDK\Resources;

use TokenManagement\SDK\Http\Client;

class DisplayResource
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function list(array $filters = []): array
    {
        $response = $this->client->get('/api/secured/displays', $filters);
        return $response['data'] ?? $response;
    }

    public function get(int $id): array
    {
        $response = $this->client->get("/api/secured/displays/{$id}");
        return $response['data'] ?? $response;
    }

    public function create(array $data): array
    {
        $response = $this->client->post('/api/secured/displays/save', $data);
        return $response['data'] ?? $response;
    }

    public function update(int $id, array $data): array
    {
        $data['id'] = $id;
        $response = $this->client->post('/api/secured/displays/save', $data);
        return $response['data'] ?? $response;
    }

    public function delete(int $id): array
    {
        return $this->client->delete("/api/secured/displays/{$id}");
    }

    public function getData(int $displayId): array
    {
        $response = $this->client->get("/api/common/displays/{$displayId}/data");
        return $response['data'] ?? $response;
    }
}
