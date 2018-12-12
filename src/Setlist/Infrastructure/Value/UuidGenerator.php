<?php

namespace Setlist\Infrastructure\Value;

use Setlist\Domain\Value\Uuid as UuidInterface;
use Setlist\Domain\Value\UuidGenerator as UuidGeneratorInterface;

class UuidGenerator implements UuidGeneratorInterface
{
    public function fromString(string $value): UuidInterface
    {
        return new Uuid($value);
    }
}
