<?php

namespace Setlist\Application\Persistence\Setlist;

interface ApplicationSetlistRepository
{
    public function getAllNames(): array;
    public function getOtherNames(string $uuid): array;
}