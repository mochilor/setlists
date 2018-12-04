<?php

namespace Setlist\Application\Command\Song;

use Setlist\Application\Command\BaseCommand;

class UpdateSong extends BaseCommand
{
    public function uuid(): string
    {
        return $this->payload()['uuid'];
    }

    public function title(): string
    {
        return $this->payload()['title'];
    }

    public function isVisible(): string
    {
        return $this->payload()['visibility'];
    }
}
