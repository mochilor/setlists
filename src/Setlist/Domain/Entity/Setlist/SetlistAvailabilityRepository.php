<?php

namespace Setlist\Domain\Entity\Setlist;

interface SetlistAvailabilityRepository
{
    public function nameIsAvailable(string $name): bool;
    public function nameIsUnique(string $name, string $uuid): bool;
}
