<?php

namespace Setlist\Domain\Entity\Setlist;

class ActFactory
{
    public function make(array $songs): Act
    {
        ksort($songs);
        $songCollection = SongCollection::create(...array_values($songs));

        return Act::create($songCollection);
    }
}
