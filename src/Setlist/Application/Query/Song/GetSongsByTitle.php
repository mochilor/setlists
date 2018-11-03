<?php

namespace Setlist\Application\Query\Song;

use Setlist\Application\Query\Query;

class GetSongsByTitle extends Query
{
    public function title(): string
    {
        return $this->payload()['title'];
    }
}
