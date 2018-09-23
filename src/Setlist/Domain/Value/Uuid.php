<?php

namespace Setlist\Domain\Value;

use Ramsey\Uuid\Uuid as RamseyUuid;
use Setlist\Domain\Exception\InvalidUuidException;

class Uuid
{
    private $uuid;

    public static function create(string $uuid): Uuid
    {
        $self = new self();
        $self->setUuid($uuid);

        return $self;
    }

    private function setUuid(string $uuid)
    {
        $this->guard($uuid);
        $this->uuid = $uuid;
    }

    private function guard(string $uuid)
    {
        if (!RamseyUuid::isValid($uuid)) {
            throw new InvalidUuidException();
        }
    }

    public static function random(): Uuid
    {
        return self::create(RamseyUuid::uuid4());
    }

    public function equals(Uuid $uuid): bool
    {
        return $uuid->uuid() === $this->uuid();
    }

    public function uuid()
    {
        return $this->uuid;
    }

    public function __toString(): string
    {
        return $this->uuid();
    }
}