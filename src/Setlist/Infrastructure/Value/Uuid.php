<?php

namespace Setlist\Infrastructure\Value;

use Setlist\Domain\Exception\InvalidUuidException;
use Setlist\Domain\Value\Uuid as UuidInterface;
use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid implements UuidInterface
{
    private $value;

    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    private function setValue(string $value)
    {
        if (!RamseyUuid::isValid($value)) {
            throw new InvalidUuidException();
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UuidInterface $uuid): bool
    {
        return $uuid->value() === $this->value();
    }
}
