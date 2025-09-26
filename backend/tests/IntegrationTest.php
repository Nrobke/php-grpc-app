<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Myecho/GreeterServiceInterface.php';
require_once __DIR__ . '/../src/Myecho/PingRequest.php';
require_once __DIR__ . '/../src/Myecho/PingResponse.php';
require_once __DIR__ . '/../src/EchoService.php';

use Myecho\PingRequest;
use Myecho\PingResponse;
use Spiral\GRPC\ContextInterface;

class IntegrationTest extends PHPUnit\Framework\TestCase
{
    public function testPhpVersionCompatibility()
    {
        // Test that we're running on PHP 7.3 or compatible
        $this->assertTrue(version_compare(PHP_VERSION, '7.3.0', '>='));
        $this->assertTrue(version_compare(PHP_VERSION, '8.0.0', '<'));
        
        echo "PHP Version: " . PHP_VERSION . "\n";
    }

    public function testRequiredExtensions()
    {
        $requiredExtensions = ['json', 'mbstring', 'xml'];
        
        foreach ($requiredExtensions as $ext) {
            $this->assertTrue(
                extension_loaded($ext),
                "Required extension '$ext' is not loaded"
            );
            echo "Extension $ext: ✓ Loaded\n";
        }
    }

    public function testProtobufClassesExist()
    {
        // Test that classes can be instantiated (this also tests class_exists)
        $pingRequest = new \Myecho\PingRequest();
        $pingResponse = new \Myecho\PingResponse();
        $echoService = new \EchoService();
        
        $this->assertInstanceOf('Myecho\PingRequest', $pingRequest);
        $this->assertInstanceOf('Myecho\PingResponse', $pingResponse);
        $this->assertInstanceOf('EchoService', $echoService);
        
        // Test interface implementation
        $this->assertInstanceOf('Myecho\GreeterServiceInterface', $echoService);
    }


    public function testContextInterfaceCompatibility()
    {
        $mockContext = $this->createMock(ContextInterface::class);
        $this->assertInstanceOf(ContextInterface::class, $mockContext);
    }
}
