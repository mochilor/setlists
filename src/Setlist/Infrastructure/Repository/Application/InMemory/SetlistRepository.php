<?php

namespace Setlist\Infrastructure\Repository\Application\InMemory;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistCollection;
use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepositoryInterface;

class SetlistRepository implements ApplicationSetlistRepositoryInterface
{
    public function getAllSetlists(int $start, int $length): PersistedSetlistCollection
    {
        return new PersistedSetlistCollection();
    }

    public function getOneSetlistById(string $id): ?PersistedSetlist
    {
        // TODO: Implement getOneSetlistById() method.
    }
}
