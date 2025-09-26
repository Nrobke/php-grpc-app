<?php

namespace Myecho;

class PingRequest
{
    protected $message = '';

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $value): self
    {
        $this->message = $value;
        return $this;
    }
}
