<?php

namespace Setlist\Infrastructure\Messaging;

class MessageFactory
{
    public function make(string $commandClassName, array $payload)
    {
        return new $commandClassName($payload);
    }
}