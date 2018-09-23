<?php

namespace Setlist\Application\Command;

class DeleteSong
{
    private $uuid;

    public static function create(string $uuid)
    {
        $command = new self();
        $command->uuid = $uuid;

        return $command;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }
}
