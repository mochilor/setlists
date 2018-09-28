<?php

namespace Setlist\Infrastructure\Messaging;

use Setlist\Application\Command\BaseCommand;
use Setlist\Infrastructure\Exception\InvalidCommandException;

class MessageFactory
{
    public function make(string $commandClassName, array $payload): BaseCommand
    {
        if (!class_exists($commandClassName) || !is_subclass_of($commandClassName, BaseCommand::class)) {
            throw new InvalidCommandException;
        }

        return new $commandClassName($payload);
    }
}
