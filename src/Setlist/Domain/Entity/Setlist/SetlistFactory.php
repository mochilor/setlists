<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTime;
use Setlist\Domain\Value\Uuid;

class SetlistFactory
{
    public function make(Uuid $id, array $songs, string $name, DateTime $date): Setlist
    {
        $songCollection = SongCollection::create(...$songs);
        return Setlist::create($id, $songCollection, $name, $date);
    }
}
