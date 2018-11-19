<?php

namespace Setlist\Application\DataTransformer;

use Setlist\Application\Persistence\Song\PersistedSong;

interface SongDataTransformer
{
    public function write(PersistedSong $song);
    public function read();
}
