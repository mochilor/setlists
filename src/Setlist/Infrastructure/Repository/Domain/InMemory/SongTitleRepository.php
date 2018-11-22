<?php

namespace Setlist\Infrastructure\Repository\Domain\InMemory;

use Setlist\Domain\Entity\Song\SongTitleRepository as SongTitleRepositoryInterface;

class SongTitleRepository implements SongTitleRepositoryInterface
{
    public function titleIsAvailable(string $title): bool
    {
        return true;
    }

    public function titleIsUnique(string $title, string $uuid): bool
    {
        return true;
    }
}
