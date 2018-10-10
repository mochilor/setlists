<?php

namespace Setlist\Application\Command;

class CreateSetlist extends BaseCommand
{
    public function name(): string
    {
        return $this->payload()['name'];
    }

    public function acts(): array
    {
        return $this->payload()['acts'];
    }

    public function date(): string
    {
        return $this->payload()['date'];
    }
}
