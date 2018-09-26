<?php

namespace Setlist\Application\Command;

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
}
