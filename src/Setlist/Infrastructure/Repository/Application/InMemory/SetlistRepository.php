<?php

namespace Setlist\Infrastructure\Repository\Application\InMemory;

use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepositoryInterface;
use Setlist\Domain\Entity\Setlist\SetlistCollection;

class SetlistRepository implements ApplicationSetlistRepositoryInterface
{
    public $names = [];

    public function getAllNames(): array
    {
        return $this->names;
    }

    public function getOtherNames(string $uuid): array
    {
        return [];
    }

    public function getAllSetlists(int $start, int $length): SetlistCollection
    {
        return new SetlistCollection();
    }
}
