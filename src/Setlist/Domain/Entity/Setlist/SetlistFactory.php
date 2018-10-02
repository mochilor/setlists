<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTimeImmutable;
use Setlist\Domain\Value\Uuid;

class SetlistFactory
{
    public function make(string $uuidString, array $acts, string $name, DateTimeImmutable $date): Setlist
    {
        $uuid = Uuid::create($uuidString);
        $actCollection = ActCollection::create(...$acts);

        return Setlist::create($uuid, $actCollection, $name, $date);
    }
}
