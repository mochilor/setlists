<?php

namespace Setlist\Infrastructure\Repository\Application\InMemory;

use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollection;
use Setlist\Application\Persistence\Song\SongRepository as ApplicationSongRepositoryInterface;

class SongRepository implements ApplicationSongRepositoryInterface
{
    public $titles = [];

    public function getAllTitles(): array
    {
        return $this->titles;
    }

    public function getOtherTitles(string $uuid): array
    {
        return $this->titles;
    }

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

    }
}
