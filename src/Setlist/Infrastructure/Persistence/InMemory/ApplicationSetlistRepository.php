<?php

namespace Setlist\Infrastructure\Persistence\InMemory;

use Setlist\Application\Persistence\Setlist\ApplicationSetlistRepository as ApplicationSetlistRepositoryInterface;

class ApplicationSetlistRepository implements ApplicationSetlistRepositoryInterface
{
    public $names = [];

    public function getAllNames(): array
    {
        return $this->names;
    }
}
