<?php

namespace Setlist\Domain\Value;

interface UuidGenerator
{
    public function fromString(string $uuid): Uuid;
}
