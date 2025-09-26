<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Myecho/GreeterServiceInterface.php';
require_once __DIR__ . '/../src/Myecho/PingRequest.php';
require_once __DIR__ . '/../src/Myecho/PingResponse.php';
require_once __DIR__ . '/../src/EchoService.php';

use Myecho\PingRequest;
use Myecho\PingResponse;
use Spiral\GRPC\ContextInterface;

class EchoServiceTest extends PHPUnit\Framework\TestCase
{
    private $echoService;
    private $mockContext;

    protected function setUp(): void
    {
        $this->echoService = new EchoService();
        $this->mockContext = $this->createMock(ContextInterface::class);
    }

    public function testPingReturnsCorrectResponse()
    {
        // Arrange
        $request = new PingRequest();
        $request->setMessage("Hello PHP 7.3!");
        
        // Act
        $response = $this->echoService->ping($this->mockContext, $request);
        
        // Assert
        $this->assertInstanceOf(PingResponse::class, $response);
        $this->assertEquals("PHP 7.3 Server received: Hello PHP 7.3!", $response->getMessage());
    }

    public function testPingWithEmptyMessage()
    {
        // Arrange
        $request = new PingRequest();
        $request->setMessage("");
        
        // Act
        $response = $this->echoService->ping($this->mockContext, $request);
        
        // Assert
        $this->assertInstanceOf(PingResponse::class, $response);
        $this->assertEquals("PHP 7.3 Server received: ", $response->getMessage());
    }

    public function testPingWithSpecialCharacters()
    {
        // Arrange
        $request = new PingRequest();
        $request->setMessage("Special chars: !@#$%^&*()_+-=[]{}|;':\",./<>?");
        
        // Act
        $response = $this->echoService->ping($this->mockContext, $request);
        
        // Assert
        $this->assertInstanceOf(PingResponse::class, $response);
        $this->assertStringContainsString("PHP 7.3 Server received:", $response->getMessage());
        $this->assertStringContainsString("Special chars:", $response->getMessage());
    }

    public function testPingWithUnicodeCharacters()
    {
        // Arrange
        $request = new PingRequest();
        $request->setMessage("Unicode: 你好世界 🌍");
        
        // Act
        $response = $this->echoService->ping($this->mockContext, $request);
        
        // Assert
        $this->assertInstanceOf(PingResponse::class, $response);
        $this->assertStringContainsString("PHP 7.3 Server received:", $response->getMessage());
        $this->assertStringContainsString("Unicode:", $response->getMessage());
    }

    public function testPingWithLongMessage()
    {
        // Arrange
        $longMessage = str_repeat("This is a long message. ", 100);
        $request = new PingRequest();
        $request->setMessage($longMessage);
        
        // Act
        $response = $this->echoService->ping($this->mockContext, $request);
        
        // Assert
        $this->assertInstanceOf(PingResponse::class, $response);
        $this->assertStringContainsString("PHP 7.3 Server received:", $response->getMessage());
        $this->assertStringContainsString("This is a long message.", $response->getMessage());
    }
}
