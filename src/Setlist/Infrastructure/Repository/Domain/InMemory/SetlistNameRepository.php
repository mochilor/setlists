<?php

namespace Setlist\Infrastructure\Repository\Domain\InMemory;

use Setlist\Domain\Entity\Setlist\SetlistNameRepository as SetlistNameRepositoryInterface;

class SetlistNameRepository implements SetlistNameRepositoryInterface
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
