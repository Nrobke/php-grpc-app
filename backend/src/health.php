<?php

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

$health = [
    'status' => 'healthy',
    'timestamp' => date('c'),
    'php_version' => PHP_VERSION,
    'extensions' => [
        'json' => extension_loaded('json'),
        'mbstring' => extension_loaded('mbstring'),
        'xml' => extension_loaded('xml')
    ],
    'grpc_ready' => class_exists('Spiral\GRPC\Server'),
    'protobuf_ready' => class_exists('Myecho\PingRequest')
];

http_response_code(200);
echo json_encode($health, JSON_PRETTY_PRINT);
