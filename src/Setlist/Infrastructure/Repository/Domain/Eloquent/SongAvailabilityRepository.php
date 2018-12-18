<?php

namespace Setlist\Infrastructure\Repository\Domain\Eloquent;

use Setlist\Domain\Entity\Song\SongAvailabilityRepository as SongAvailabilityRepositoryInterface;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Song as EloquentSong;

class SongAvailabilityRepository implements SongAvailabilityRepositoryInterface
{
    public function titleIsAvailable(string $title): bool
    {
        return empty(EloquentSong::where('title', $title)->first());
    }

    public function titleIsUnique(string $title, string $uuid): bool
    {
        $songsCollection = EloquentSong::where('title', $title)
            ->where('id', '<>', $uuid)
            ->get();

        return $songsCollection->isEmpty();
    }

    public function idIsAvailable(string $id): bool
    {
        return empty(EloquentSong::find($id));
    }
}
