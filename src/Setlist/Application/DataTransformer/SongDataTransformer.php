<?php

namespace Setlist\Application\DataTransformer;

use Setlist\Domain\Entity\Song\Song;

interface SongDataTransformer
{
    public function write(Song $song);
    public function read();
}
