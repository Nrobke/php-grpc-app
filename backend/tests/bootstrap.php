<?php

// Bootstrap file for PHPUnit tests
require_once __DIR__ . '/../vendor/autoload.php';

// Set up error reporting for tests
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP Version: " . PHP_VERSION . "\n";
echo "Running tests for PHP 7.3 gRPC application...\n\n";
