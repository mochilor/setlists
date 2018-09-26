<?php

namespace Setlist\Application\Command;

abstract class BaseCommand
{
    private $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    protected function payload(): array
    {
        return $this->payload;
    }
}
