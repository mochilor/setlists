<?php

namespace Setlist\Application\Persistence\Setlist;

use Setlist\Domain\Entity\Setlist\SetlistCollection;

interface SetlistRepository
{
    public function getAllNames(): array;
    public function getOtherNames(string $uuid): array;
    public function getAllSetlists(int $start, int $length): SetlistCollection;
    public function getSetlistSongsCount(string $uuid): int;
}