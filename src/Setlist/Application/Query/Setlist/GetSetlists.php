<?php

namespace Setlist\Application\Query\Setlist;

use Setlist\Application\Query\Query;

class GetSetlists extends Query
{
    public function start(): string
    {
        return $this->payload()['start'];
    }

    public function length(): string
    {
        return $this->payload()['length'];
    }
}
