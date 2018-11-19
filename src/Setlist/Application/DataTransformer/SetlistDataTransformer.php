<?php

namespace Setlist\Application\DataTransformer;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;

interface SetlistDataTransformer
{
    public function write(PersistedSetlist $setlist);
    public function read();
}
