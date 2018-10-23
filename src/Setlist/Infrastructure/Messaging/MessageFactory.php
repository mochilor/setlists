<?php

namespace Setlist\Infrastructure\Messaging;

use Setlist\Application\Command\BaseCommand;
use Setlist\Application\Query\Query;
use Setlist\Infrastructure\Exception\InvalidCommandException;
use Setlist\Infrastructure\Exception\InvalidQueryException;

class MessageFactory
{
    public function makeCommand(string $commandClassName, array $payload): BaseCommand
    {
        if (!class_exists($commandClassName) || !is_subclass_of($commandClassName, BaseCommand::class)) {
            throw new InvalidCommandException;
        }

        return new $commandClassName($payload);
    }

    public function makeQuery(string $queryClassName, array $payload): Query
    {
        if (!class_exists($queryClassName) || !is_subclass_of($queryClassName, Query::class)) {
            throw new InvalidQueryException();
        }

        return new $queryClassName($payload);
    }
}
