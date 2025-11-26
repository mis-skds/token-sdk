<?php

/**
 * Example: Complete Token Workflow
 * 
 * This example demonstrates the complete token lifecycle:
 * Issue → Call → Serve → Complete
 */

require __DIR__ . '/../vendor/autoload.php';

use TokenManagement\SDK\TokenClient;
use TokenManagement\SDK\Exceptions\ApiException;

try {
    $client = new TokenClient(['base_url' => 'http://localhost:8080']);
    
    // Authenticate
    $auth = $client->auth()->signIn('user@example.com', 'password');
    $client->setAccessToken($auth->getAccessToken());

    $locationId = 1;
    $servicePointId = 2;

    // Step 1: Issue a token
    echo "Step 1: Issuing token...\n";
    $token = $client->tokens()->issue([
        'mlocation_id' => $locationId,
        'mservicepoint_id' => $servicePointId,
        'mtokencategory_id' => 1,
        'customer_name' => 'Jane Smith'
    ]);
    echo "✓ Token issued: {$token->getTokenNumber()} (Status: {$token->getStatus()})\n\n";

    // Step 2: Call the next token
    echo "Step 2: Calling next token...\n";
    $calledToken = $client->tokens()->callNext($locationId, $servicePointId);
    echo "✓ Now calling: {$calledToken->getTokenNumber()} (Status: {$calledToken->getStatus()})\n\n";

    // Step 3: Check currently serving
    echo "Step 3: Checking currently serving tokens...\n";
    $serving = $client->tokens()->currentlyServing($locationId);
    foreach ($serving as $item) {
        echo "  - {$item['token_number']} at {$item['servicepoint_name']}\n";
    }
    echo "\n";

    // Step 4: Complete the token
    echo "Step 4: Completing token...\n";
    $completedToken = $client->tokens()->complete(
        $calledToken->getId(),
        $locationId,
        $servicePointId
    );
    echo "✓ Token completed: {$completedToken->getTokenNumber()} (Status: {$completedToken->getStatus()})\n\n";

    echo "Workflow completed successfully!\n";

} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
