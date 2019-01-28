<?php

namespace Setlist\Application\Query\Song;

use Setlist\Application\Query\Query;

class GetSongs extends Query
{
    public function start(): string
    {
        return $this->payload()['start'];
    }

    public function length(): string
    {
        return $this->payload()['length'];
    }

    public function title(): string
    {
        return $this->payload()['title'];
    }

    public function notIn(): string
    {
        return $this->payload()['notIn'];
    }
}
