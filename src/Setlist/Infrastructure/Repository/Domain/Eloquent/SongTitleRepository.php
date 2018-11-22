<?php

namespace Setlist\Infrastructure\Repository\Domain\Eloquent;

use Setlist\Domain\Entity\Song\SongTitleRepository as SongTitleRepositoryInterface;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Song as EloquentSong;

class SongTitleRepository implements SongTitleRepositoryInterface
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
}
