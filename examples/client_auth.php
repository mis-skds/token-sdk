<?php

/**
 * Example: Client Authentication (Machine-to-Machine)
 * 
 * This example demonstrates how to use the SDK with Client ID and Secret
 * instead of user login. This is useful for Kiosks, Displays, and background jobs.
 */

require __DIR__ . '/../vendor/autoload.php';

use TokenManagement\SDK\TokenClient;
use TokenManagement\SDK\Exceptions\ApiException;

try {
    // Initialize with Client Credentials
    $client = new TokenClient([
        'base_url' => 'http://localhost:8080',
        'client_id' => 'YOUR_CLIENT_ID',
        'client_secret' => 'YOUR_CLIENT_SECRET'
    ]);

    echo "Initialized in Client Auth Mode\n\n";

    // 1. Get Display Data (No user login needed)
    echo "Fetching display data...\n";
    $displayData = $client->displays()->getData(1);
    print_r($displayData);
    echo "\n";

    // 2. Issue Token as System
    echo "Issuing token as system...\n";
    $token = $client->tokens()->issue([
        'mlocation_id' => 1,
        'mservicepoint_id' => 2,
        'mtokencategory_id' => 1,
        'customer_name' => 'Kiosk User'
    ]);
    echo "Token Issued: " . $token->getTokenNumber() . "\n";

} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
