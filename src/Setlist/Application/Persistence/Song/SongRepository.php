<?php

namespace Setlist\Application\Persistence\Song;

use Setlist\Domain\Entity\Setlist\SongCollection;

interface SongRepository
{
    public function getAllTitles(): array;
    public function getOtherTitles(string $uuid): array;
    public function getAllSongs(int $start, int $length): SongCollection;
    public function getSongsByTitle(string $title): SongCollection;
}
