<?php

require_once __DIR__ . '/../vendor/autoload.php';

echo "PHP 7.3 Alpine is working!\n";
echo "PHP Version: " . phpversion() . "\n";

// Test if required extensions are loaded
$required = ['json', 'mbstring', 'xml'];
foreach ($required as $ext) {
    echo "Extension $ext: " . (extension_loaded($ext) ? "✓ Loaded" : "✗ Missing") . "\n";
}

// Test if gRPC classes are available
echo "gRPC Classes:\n";
echo "  Spiral\\GRPC\\Server: " . (class_exists('Spiral\GRPC\Server') ? "✓ Available" : "✗ Missing") . "\n";
echo "  Myecho\\PingRequest: " . (class_exists('Myecho\PingRequest') ? "✓ Available" : "✗ Missing") . "\n";
echo "  Myecho\\PingResponse: " . (class_exists('Myecho\PingResponse') ? "✓ Available" : "✗ Missing") . "\n";