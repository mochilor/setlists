<?php

namespace Setlist\Application\Persistence\Song;

interface SongRepository
{
    public function getAllTitles(): array;
    public function getOtherTitles(string $uuid): array;
}