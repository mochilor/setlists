<?php

namespace Setlist\Infrastructure\Repository\Application\InMemory;

use Setlist\Application\Persistence\Song\SongRepository as ApplicationSongRepositoryInterface;
use Setlist\Domain\Entity\Setlist\SongCollection;

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

    public function getAllSongs(int $start, int $length): SongCollection
    {
        return new SongCollection();
    }
}
