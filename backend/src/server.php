<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/EchoService.php';

use Spiral\GRPC\Server;
use Spiral\RoadRunner\Worker;
use Spiral\Goridge\StreamRelay;
use Myecho\GreeterServiceInterface;

// Create server instance
$server = new Server();

// Register our service
$server->registerService(GreeterServiceInterface::class, new EchoService());

// Start server
$relay = new StreamRelay(STDIN, STDOUT);
$worker = new Worker($relay);
echo "gRPC PHP 7.3 Alpine server running on port 9001...\n";

try {
    $server->serve($worker);
} catch (Throwable $e) {
    $worker->error("Server error: " . $e->getMessage());
}
