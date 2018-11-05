<?php

namespace Setlist\Application\DataTransformer;

use Setlist\Domain\Entity\Setlist\Setlist;

interface SetlistDataTransformer
{
    public function write(Setlist $setlist);
    public function read();
}
