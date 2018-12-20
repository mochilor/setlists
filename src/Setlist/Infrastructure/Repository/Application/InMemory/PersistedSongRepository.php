<?php

namespace Setlist\Infrastructure\Repository\Application\InMemory;

use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollection;
use Setlist\Application\Persistence\Song\PersistedSongRepository as ApplicationSongRepositoryInterface;

class PersistedSongRepository implements ApplicationSongRepositoryInterface
{
    public function getAllSongs(int $start, int $length): PersistedSongCollection
    {
        return new PersistedSongCollection();
    }

    public function getSongsByTitle(string $title): PersistedSongCollection
    {
        return new PersistedSongCollection();
    }

    public function getOneSongById(string $id): ?PersistedSong
    {
        return null;
    }
}
