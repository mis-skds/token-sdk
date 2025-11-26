<?php

namespace TokenManagement\SDK\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use TokenManagement\SDK\Exceptions\ApiException;
use TokenManagement\SDK\Exceptions\AuthenticationException;
use TokenManagement\SDK\Exceptions\ValidationException;

/**
 * HTTP Client wrapper
 * 
 * Handles all HTTP communication with the API
 * 
 * @package TokenManagement\SDK\Http
 */
class Client
{
    /** @var GuzzleClient */
    private $client;

    /** @var string */
    private $baseUrl;

    /** @var string|null */
    private $accessToken;

    /** @var string|null */
    private $clientId;

    /** @var string|null */
    private $clientSecret;

    /**
     * Create a new HTTP client
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->baseUrl = rtrim($config['base_url'], '/');
        $this->clientId = $config['client_id'] ?? null;
        $this->clientSecret = $config['client_secret'] ?? null;
        
        $this->client = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'timeout' => $config['timeout'] ?? 30,
            'verify' => $config['verify_ssl'] ?? true,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * Set the access token for authenticated requests
     *
     * @param string $token
     * @return void
     */
    public function setAccessToken(string $token): void
    {
        $this->accessToken = $token;
    }

    /**
     * Make a GET request
     *
     * @param string $endpoint
     * @param array $query
     * @return array
     * @throws ApiException
     */
    public function get(string $endpoint, array $query = []): array
    {
        return $this->request('GET', $endpoint, ['query' => $query]);
    }

    /**
     * Make a POST request
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws ApiException
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, ['json' => $data]);
    }

    /**
     * Make a PUT request
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws ApiException
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * Make a DELETE request
     *
     * @param string $endpoint
     * @return array
     * @throws ApiException
     */
    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * Make an HTTP request
     *
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return array
     * @throws ApiException
     */
    private function request(string $method, string $endpoint, array $options = []): array
    {
        try {
            // Add authorization header if token is set (User Auth)
            if ($this->accessToken) {
                $options['headers']['Authorization'] = 'Bearer ' . $this->accessToken;
            }
            // Fallback to Client Auth if configured and no user token
            elseif ($this->clientId && $this->clientSecret) {
                $credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");
                $options['headers']['X-Client-Auth'] = 'Basic ' . $credentials;
            }

            $response = $this->client->request($method, $endpoint, $options);
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ApiException('Invalid JSON response from API');
            }

            // Check for API-level errors
            if (isset($data['status']) && $data['status'] === false) {
                $this->handleApiError($data, $response->getStatusCode());
            }

            return $data;

        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e);
        }
    }

    /**
     * Handle API-level errors
     *
     * @param array $data
     * @param int $statusCode
     * @throws ApiException
     */
    private function handleApiError(array $data, int $statusCode): void
    {
        $errors = $data['errors'] ?? ['Unknown error'];
        $message = is_array($errors) ? json_encode($errors) : $errors;

        if ($statusCode === 401 || $statusCode === 203) {
            throw new AuthenticationException($message, $statusCode);
        }

        if ($statusCode === 400) {
            throw new ValidationException($message, $statusCode, $errors);
        }

        throw new ApiException($message, $statusCode);
    }

    /**
     * Handle Guzzle exceptions
     *
     * @param GuzzleException $e
     * @throws ApiException
     */
    private function handleGuzzleException(GuzzleException $e): void
    {
        if (method_exists($e, 'getResponse') && $e->getResponse()) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $data = json_decode($body, true);

            if ($data && isset($data['errors'])) {
                $this->handleApiError($data, $statusCode);
            }

            throw new ApiException($e->getMessage(), $statusCode, $e);
        }

        throw new ApiException($e->getMessage(), 0, $e);
    }
}
