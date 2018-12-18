<?php

namespace Setlist\Infrastructure\Repository\Domain\InMemory;

use Setlist\Domain\Entity\Setlist\SetlistAvailabilityRepository as SetlistAvailabilityRepositoryInterface;

class SetlistAvailabilityRepository implements SetlistAvailabilityRepositoryInterface
{
    public function nameIsAvailable(string $name): bool
    {
        return true;
    }

    public function nameIsUnique(string $name, string $uuid): bool
    {
        return true;
    }
}
