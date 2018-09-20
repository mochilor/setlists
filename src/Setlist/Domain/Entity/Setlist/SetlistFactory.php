<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTime;
use Setlist\Domain\Value\Uuid;

class SetlistFactory
{
    public function make(Uuid $id, array $acts, string $name, DateTime $date): Setlist
    {
        $actCollection = ActCollection::create(...$acts);
        return Setlist::create($id, $actCollection, $name, $date);
    }
}
