<?php

namespace Setlist\Application\Persistence\Song;

interface ApplicationSongRepository
{
    public function getAllTitles(): array;
    public function getOtherTitles(string $uuid): array;
}