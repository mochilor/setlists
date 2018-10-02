<?php

namespace Setlist\Domain\Entity\Song;

use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Domain\Value\Uuid;

class SongFactory
{
    private $eventsTrigger;

    public function __construct(EventsTrigger $eventsTrigger)
    {
        $this->eventsTrigger = $eventsTrigger;
    }

    public function make(string $uuidString, string $title): Song
    {
        $uuid = Uuid::create($uuidString);
        $this->eventsTrigger->trigger(SongWasCreated::create($uuid, $title));

        return Song::create($uuid, $title, $this->eventsTrigger);
    }

    public function restore(string $uuidString, string $title): Song
    {
        $uuid = Uuid::create($uuidString);

        return Song::create($uuid, $title, $this->eventsTrigger);
    }
}
