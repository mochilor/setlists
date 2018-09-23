<?php

namespace Setlist\Application\Command;

class UpdateSong
{
    private $uuid;
    private $title;

    public static function create(string $uuid, string $title)
    {
        $command = new self();
        $command->uuid = $uuid;
        $command->title = $title;

        return $command;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function title(): string
    {
        return $this->title;
    }
}
