<?php

namespace Setlist\Infrastructure\Repository\Domain\InMemory;

use Setlist\Domain\Entity\Song\SongAvailabilityRepository as SongAvailabilityRepositoryInterface;

class SongAvailabilityRepository implements SongAvailabilityRepositoryInterface
{
    public function titleIsAvailable(string $title): bool
    {
        return true;
    }

    public function titleIsUnique(string $title, string $uuid): bool
    {
        return true;
    }

    public function idIsAvailable(string $id): bool
    {
        return true;
    }
}
