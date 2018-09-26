<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTimeImmutable;
use Setlist\Domain\Value\Uuid;

class SetlistFactory
{
    public function make(Uuid $id, array $acts, string $name, DateTimeImmutable $date): Setlist
    {
        $actCollection = ActCollection::create(...$acts);
        return Setlist::create($id, $actCollection, $name, $date);
    }
}
