<?php

namespace Setlist\Application\Query;

abstract class Query
{
    protected $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    protected function payload(): array
    {
        return $this->payload;
    }
}
