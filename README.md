# Token Management PHP SDK

Official PHP SDK for the Token Management API - A queue management system client library.

[![PHP Version](https://img.shields.io/badge/php-%5E7.4%7C%5E8.0-blue)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

## Installation

Install via Composer:

```bash
composer require codesbytvt/token-management-sdk
```

## Quick Start

```php
<?php

require 'vendor/autoload.php';

use TokenManagement\SDK\TokenClient;

// Initialize the client
// Option 1: User Authentication (JWT)
$client = new TokenClient([
    'base_url' => 'https://api.yourdomain.com'
]);

// Option 2: Client Authentication (API Key/Secret)
// Best for Kiosks, Displays, and Machine-to-Machine
$client = new TokenClient([
    'base_url' => 'https://api.yourdomain.com',
    'client_id' => 'YOUR_CLIENT_ID',
    'client_secret' => 'YOUR_CLIENT_SECRET'
]);


// Authenticate
$auth = $client->auth()->signIn('user@example.com', 'password');
$client->setAccessToken($auth->getAccessToken());

// Issue a token
$token = $client->tokens()->issue([
    'mlocation_id' => 1,
    'mservicepoint_id' => 2,
    'mtokencategory_id' => 1,
    'customer_name' => 'John Doe'
]);

echo "Token Number: " . $token->getTokenNumber();
```

## Features

✅ **Full API Coverage** - All endpoints wrapped in clean interfaces  
✅ **Type Hints** - PHP 7.4+ type declarations  
✅ **Fluent API** - Intuitive, chainable methods  
✅ **Error Handling** - Custom exceptions for different error types  
✅ **JWT Authentication** - Automatic token management  
✅ **Models** - Strongly-typed data objects  
✅ **PSR-4 Autoloading** - Modern PHP standards  

## Usage

### Authentication

#### Sign Up
```php
$auth = $client->auth()->signUp(
    'John',                    // First name
    'john@example.com',        // Email/username
    'SecurePassword123'        // Password
);

$accessToken = $auth->getAccessToken();
$refreshToken = $auth->getRefreshToken();
```

#### Sign In
```php
$auth = $client->auth()->signIn('user@example.com', 'password');
$client->setAccessToken($auth->getAccessToken());
```

#### Sign In with Google
```php
$auth = $client->auth()->signInWithGoogle($googleAuthCode);
$client->setAccessToken($auth->getAccessToken());
```

#### Refresh Token
```php
$auth = $client->auth()->refreshToken($refreshToken);
$client->setAccessToken($auth->getAccessToken());
```

### Token Management

#### Issue a Token
```php
$token = $client->tokens()->issue([
    'mlocation_id' => 1,
    'mservicepoint_id' => 2,
    'mtokencategory_id' => 1,
    'customer_name' => 'John Doe',
    'customer_phone' => '+1234567890'
]);

echo $token->getTokenNumber(); // "G042"
echo $token->getStatus();      // 1 (WAITING)
```

#### Call Next Token
```php
$token = $client->tokens()->callNext(
    1,  // Location ID
    2   // Service Point ID
);

echo "Now serving: " . $token->getTokenNumber();
```

#### Call Specific Token
```php
$token = $client->tokens()->callById(
    123,  // Token ID
    1,    // Location ID
    2     // Service Point ID
);
```

#### Skip Token
```php
$token = $client->tokens()->skip(123, 1, 2);
```

#### Complete Token
```php
$token = $client->tokens()->complete(123, 1, 2);
```

#### Get Currently Serving Tokens
```php
$serving = $client->tokens()->currentlyServing(1); // Location ID
foreach ($serving as $token) {
    echo $token['token_number'] . " at " . $token['servicepoint_name'] . "\n";
}
```

#### List Tokens
```php
$tokens = $client->tokens()->list([
    'mlocation_id' => 1,
    'status' => 1,  // WAITING
    'page' => 1,
    'pageLength' => 20
]);
```

### Location Management

```php
// Create location
$location = $client->locations()->create([
    'name' => 'Main Branch',
    'address' => '123 Main St',
    'city' => 'New York',
    'status' => 1
]);

// List locations
$locations = $client->locations()->list();

// Get specific location
$location = $client->locations()->get(1);

// Update location
$location = $client->locations()->update(1, [
    'name' => 'Updated Name'
]);

// Delete location
$client->locations()->delete(1);
```

### Service Point Management

```php
// Create service point
$servicePoint = $client->servicePoints()->create([
    'mlocation_id' => 1,
    'name' => 'Counter 1',
    'display_label' => 'C1',
    'status' => 1
]);

// List service points
$servicePoints = $client->servicePoints()->list([
    'mlocation_id' => 1
]);
```

### Token Category Management

```php
// Create category
$category = $client->tokenCategories()->create([
    'name' => 'General',
    'prefix' => 'G',
    'status' => 1
]);

// List categories
$categories = $client->tokenCategories()->list();
```

### Display Management

```php
// Create display
$display = $client->displays()->create([
    'mlocation_id' => 1,
    'name' => 'Main Display',
    'status' => 1
]);

// Get display data (public endpoint, no auth required)
$displayData = $client->displays()->getData(1);
```

## Configuration

### Available Options

```php
$client = new TokenClient([
    'base_url' => 'https://api.yourdomain.com',  // Required
    'timeout' => 30,                              // Optional, default: 30
    'verify_ssl' => true                          // Optional, default: true
]);
```

## Error Handling

The SDK throws specific exceptions for different error types:

```php
use TokenManagement\SDK\Exceptions\ApiException;
use TokenManagement\SDK\Exceptions\AuthenticationException;
use TokenManagement\SDK\Exceptions\ValidationException;

try {
    $auth = $client->auth()->signIn('user@example.com', 'wrong-password');
} catch (AuthenticationException $e) {
    echo "Authentication failed: " . $e->getMessage();
} catch (ValidationException $e) {
    echo "Validation errors: ";
    print_r($e->getValidationErrors());
} catch (ApiException $e) {
    echo "API error: " . $e->getMessage();
}
```

## Token Status Constants

```php
1 - WAITING
2 - CALLED
3 - SERVING
4 - COMPLETED
5 - SKIPPED
```

## Complete Example

```php
<?php

require 'vendor/autoload.php';

use TokenManagement\SDK\TokenClient;
use TokenManagement\SDK\Exceptions\ApiException;

try {
    // Initialize
    $client = new TokenClient([
        'base_url' => 'https://api.yourdomain.com'
    ]);

    // Authenticate
    $auth = $client->auth()->signIn('admin@example.com', 'password');
    $client->setAccessToken($auth->getAccessToken());

    // Create a location
    $location = $client->locations()->create([
        'name' => 'Downtown Branch',
        'city' => 'New York'
    ]);

    // Create a service point
    $servicePoint = $client->servicePoints()->create([
        'mlocation_id' => $location['id'],
        'name' => 'Counter 1',
        'display_label' => 'C1'
    ]);

    // Create a token category
    $category = $client->tokenCategories()->create([
        'name' => 'General',
        'prefix' => 'G'
    ]);

    // Issue a token
    $token = $client->tokens()->issue([
        'mlocation_id' => $location['id'],
        'mservicepoint_id' => $servicePoint['id'],
        'mtokencategory_id' => $category['id'],
        'customer_name' => 'John Doe'
    ]);

    echo "Token issued: " . $token->getTokenNumber() . "\n";

    // Call the token
    $calledToken = $client->tokens()->callNext(
        $location['id'],
        $servicePoint['id']
    );

    echo "Now serving: " . $calledToken->getTokenNumber() . "\n";

    // Complete the token
    $completedToken = $client->tokens()->complete(
        $calledToken->getId(),
        $location['id'],
        $servicePoint['id']
    );

    echo "Token completed!\n";

} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## Requirements

- PHP 7.4 or higher
- Guzzle HTTP client
- JSON extension

## License

MIT License - see LICENSE file for details

## Support

For issues and questions:
- GitHub Issues: [Your repo URL]
- Documentation: [API Documentation](../DOCUMENTATION.md)
- API Reference: [API Reference](../API_REFERENCE.md)

## Contributing

Contributions are welcome! Please submit pull requests or open issues on GitHub.
