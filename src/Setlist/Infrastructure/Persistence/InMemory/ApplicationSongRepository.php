<?php

namespace Setlist\Infrastructure\Persistence\InMemory;

use Setlist\Application\Persistence\Song\ApplicationSongRepository as ApplicationSongRepositoryInterface;

class ApplicationSongRepository implements ApplicationSongRepositoryInterface
{
    public $titles = [];

    public function getAllTitles(): array
    {
        return $this->titles;
    }
}
