<?php

namespace Setlist\Application\Command\Song;

use Setlist\Application\Command\BaseCommand;

class DeleteSong extends BaseCommand
{
    public function uuid(): string
    {
        return $this->payload()['uuid'];
    }
}
