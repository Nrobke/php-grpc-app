<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Myecho/GreeterServiceInterface.php';
require_once __DIR__ . '/Myecho/PingRequest.php';
require_once __DIR__ . '/Myecho/PingResponse.php';

use Myecho\GreeterServiceInterface;
use Myecho\PingRequest;
use Myecho\PingResponse;
use Spiral\GRPC\ContextInterface;

class EchoService implements GreeterServiceInterface
{
    public function ping(ContextInterface $ctx, PingRequest $request): PingResponse
    {
        $response = new PingResponse();
        $response->setMessage("PHP 7.3 Server received: " . $request->getMessage());
        
        return $response;
    }
}