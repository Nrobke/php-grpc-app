<?php

namespace Myecho;

use Spiral\GRPC\ContextInterface;
use Spiral\GRPC\ServiceInterface;

interface GreeterServiceInterface extends ServiceInterface
{
    public const NAME = 'myecho.GreeterService';
    
    public function ping(ContextInterface $ctx, PingRequest $request): PingResponse;
}
