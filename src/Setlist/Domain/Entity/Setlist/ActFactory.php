<?php

namespace Setlist\Domain\Entity\Setlist;

class ActFactory
{
    public function make(array $songs): Act
    {
        $songCollection = SongCollection::create(...$songs);
        return Act::create($songCollection);
    }
}
