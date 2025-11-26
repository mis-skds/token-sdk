<?php

/**
 * Example: Basic Usage
 * 
 * This example demonstrates basic SDK usage including
 * authentication and token issuance.
 */

require __DIR__ . '/../vendor/autoload.php';

use TokenManagement\SDK\TokenClient;
use TokenManagement\SDK\Exceptions\ApiException;

try {
    // Initialize the client
    $client = new TokenClient([
        'base_url' => 'http://localhost:8080'
    ]);

    // Authenticate
    echo "Authenticating...\n";
    $auth = $client->auth()->signIn('user@example.com', 'password');
    $client->setAccessToken($auth->getAccessToken());
    
    echo "Logged in as: " . $auth->getUsername() . "\n";
    echo "Access Token: " . substr($auth->getAccessToken(), 0, 20) . "...\n\n";

    // Issue a token
    echo "Issuing a new token...\n";
    $token = $client->tokens()->issue([
        'mlocation_id' => 1,
        'mservicepoint_id' => 2,
        'mtokencategory_id' => 1,
        'customer_name' => 'John Doe',
        'customer_phone' => '+1234567890'
    ]);

    echo "Token issued successfully!\n";
    echo "Token Number: " . $token->getTokenNumber() . "\n";
    echo "Status: " . $token->getStatus() . "\n";
    echo "Created At: " . $token->getCreatedAt() . "\n";

} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
}
