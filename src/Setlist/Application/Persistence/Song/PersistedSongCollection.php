<?php

namespace Setlist\Application\Persistence\Song;

use ArrayObject;

class PersistedSongCollection extends ArrayObject
{
    public static function create(PersistedSong ...$songs): self
    {
        return new self($songs, self::ARRAY_AS_PROPS);
    }
}
