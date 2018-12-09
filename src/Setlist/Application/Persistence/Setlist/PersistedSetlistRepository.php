<?php

namespace Setlist\Application\Persistence\Setlist;

interface PersistedSetlistRepository
{
    public function getOneSetlistById(string $id): ?PersistedSetlist;
    public function getAllSetlists(int $start, int $length): PersistedSetlistCollection;
}