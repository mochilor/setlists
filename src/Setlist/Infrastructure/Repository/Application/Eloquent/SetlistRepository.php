<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent;

use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepositoryInterface;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Setlist as EloquentSetlist;

class SetlistRepository implements ApplicationSetlistRepositoryInterface
{
    public function getSelistsCountBySongId(string $id): int
    {
        $setlistsCount = EloquentSetlist::whereHas('songs', function ($query) use ($id) {
            $query->where('id', $id);
        })->count();

        return $setlistsCount;
    }
}
