<?php

namespace Setlist\Infrastructure\Repository\Domain\Eloquent;

use Setlist\Domain\Entity\Setlist\SetlistAvailabilityRepository as SetlistAvailabilityRepositoryInterface;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Setlist as EloquentSetlist;

class SetlistAvailabilityRepository implements SetlistAvailabilityRepositoryInterface
{
    public function nameIsAvailable(string $name): bool
    {
        return empty(EloquentSetlist::where('name', $name)->first());
    }

    public function nameIsUnique(string $name, string $uuid): bool
    {
        $setlistsCollection = EloquentSetlist::where('name', $name)
            ->where('id', '<>', $uuid)
            ->get();

        return $setlistsCollection->isEmpty();
    }
}
