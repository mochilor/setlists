<?php

namespace Setlist\Application\Persistence\Song;

interface ApplicationSongRepository
{
    public function getAllTitles(): array;
}