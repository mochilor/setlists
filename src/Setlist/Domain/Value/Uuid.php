<?php

namespace Setlist\Domain\Value;

interface Uuid
{
    public function value(): string;
    public function equals(Uuid $uuid): bool;
}
