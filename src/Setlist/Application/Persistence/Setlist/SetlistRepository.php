<?php

namespace Setlist\Application\Persistence\Setlist;

interface SetlistRepository
{
    public function getOneSetlistById(string $id): ?PersistedSetlist;
    public function getAllSetlists(int $start, int $length): PersistedSetlistCollection;
}