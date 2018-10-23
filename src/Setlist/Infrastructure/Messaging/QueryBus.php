<?php

namespace Setlist\Infrastructure\Messaging;

use Setlist\Application\Query\Query;
use Setlist\Infrastructure\Exception\InvalidQueryException;

class QueryBus
{
    private $handlers = [];

    public function addHandler(string $queryName, $handler)
    {
        $this->handlers[$queryName] = $handler;
    }

    public function handle(Query $query)
    {
        if (!isset($this->handlers[get_class($query)])) {
            throw new InvalidQueryException('The given query can not be handled');
        }

        $queryHandler = $this->handlers[get_class($query)];

        return $queryHandler($query);
    }
}
