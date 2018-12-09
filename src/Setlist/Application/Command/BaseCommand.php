<?php

namespace Setlist\Application\Command;

abstract class BaseCommand
{
    protected $payload;

    const SUCCESS_CODE = 200;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    protected function payload(): array
    {
        return $this->payload;
    }

    public function successCode(): int
    {
        return self::SUCCESS_CODE;
    }
}
