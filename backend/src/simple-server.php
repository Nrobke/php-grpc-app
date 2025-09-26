<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Myecho/PingRequest.php';
require_once __DIR__ . '/Myecho/PingResponse.php';

use Myecho\PingRequest;
use Myecho\PingResponse;

// Simple HTTP server for demonstration
$host = '0.0.0.0';
$port = 9001;

echo "Starting PHP 7.3 HTTP server on $host:$port...\n";

// Use PHP's built-in development server
$router = __DIR__ . '/router.php';
$command = "php -S $host:$port -t " . __DIR__ . " $router";
echo "PHP 7.3 HTTP server listening on $host:$port\n";
echo "Command: $command\n";

// Execute the server
passthru($command);
