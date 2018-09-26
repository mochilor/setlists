<?php

namespace Setlist\Application\Command;

class CreateSong extends BaseCommand
{
    public function title(): string
    {
        return $this->payload()['title'];
    }
}
