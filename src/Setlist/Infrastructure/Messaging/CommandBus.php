<?php

namespace Setlist\Infrastructure\Messaging;

use Setlist\Application\Command\BaseCommand;
use Setlist\Infrastructure\Exception\InvalidCommandException;

class CommandBus
{
    private $handlers = [];

    public function addHandler(string $commandName, $handler)
    {
        $this->handlers[$commandName] = $handler;
    }

    public function handle(BaseCommand $command)
    {
        if (!isset($this->handlers[get_class($command)])) {
            throw new InvalidCommandException('The given command can not be handled');
        }

        $commandHandler = $this->handlers[get_class($command)];

        return $commandHandler($command);
    }
}