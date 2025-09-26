<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Myecho/PingRequest.php';
require_once __DIR__ . '/Myecho/PingResponse.php';

use Myecho\PingRequest;
use Myecho\PingResponse;

// Get the request URI
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string from URI
$path = parse_url($uri, PHP_URL_PATH);

// Handle different endpoints
if ($path === '/health.php') {
    handleHealthCheck();
} elseif ($path === '/ping' && $method === 'POST') {
    handlePing();
} else {
    handleNotFound();
}

function handleHealthCheck() {
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
    
    header('Content-Type: application/json');
    echo json_encode($health, JSON_PRETTY_PRINT);
}

function handlePing() {
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data || !isset($data['message'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Bad Request'], JSON_PRETTY_PRINT);
        return;
    }
    
    // Create response using our custom classes
    $pingRequest = new PingRequest();
    $pingRequest->setMessage($data['message']);
    
    $pingResponse = new PingResponse();
    $pingResponse->setMessage("PHP 7.3 Server received: " . $pingRequest->getMessage());
    
    $response = [
        'message' => $pingResponse->getMessage(),
        'timestamp' => date('c'),
        'php_version' => PHP_VERSION
    ];
    
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    echo json_encode($response, JSON_PRETTY_PRINT);
}

function handleNotFound() {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not Found'], JSON_PRETTY_PRINT);
}
