<?php

namespace Setlist\Application\Persistence\Setlist;

interface SetlistRepository
{
    public function getSelistsCountBySongId(string $id): int;
}