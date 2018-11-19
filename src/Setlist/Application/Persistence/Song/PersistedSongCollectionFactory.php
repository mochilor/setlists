<?php

namespace Setlist\Application\Persistence\Song;

class PersistedSongCollectionFactory
{
    public function make(array $songs): PersistedSongCollection
    {
        ksort($songs);
        return PersistedSongCollection::create(...array_values($songs));
    }
}
