<?php

namespace Setlist\Domain\Entity\Song;

use DateTimeImmutable;
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
        $dateTime = new DateTimeImmutable();
        $this->eventsTrigger->trigger(
            SongWasCreated::create($uuid, $title, $dateTime->format(Song::CREATION_DATE_FORMAT))
        );

        return Song::create($uuid, $title, $dateTime, $this->eventsTrigger);
    }

    public function restore(string $uuidString, string $title, string $formattedDateTime): Song
    {
        $uuid = Uuid::create($uuidString);
        $dateTime = DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, $formattedDateTime);

        return Song::create($uuid, $title, $dateTime, $this->eventsTrigger);
    }
}
