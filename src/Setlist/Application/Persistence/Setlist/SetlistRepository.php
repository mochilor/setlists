<?php

namespace Setlist\Application\Persistence\Setlist;

interface SetlistRepository
{
    public function getAllNames(): array;
    public function getOtherNames(string $uuid): array;
}