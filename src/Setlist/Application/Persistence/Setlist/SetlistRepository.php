<?php

namespace Setlist\Application\Persistence\Setlist;

interface SetlistRepository
{
    public function getAllNames(): array;
    public function getOtherNames(string $uuid): array;
    public function getOneSetlistById(string $id): ?PersistedSetlist;
    public function getAllSetlists(int $start, int $length): PersistedSetlistCollection;
}