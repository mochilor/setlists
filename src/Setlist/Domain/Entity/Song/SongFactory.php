<?php

namespace Setlist\Domain\Entity\Song;

use DateTimeImmutable;
use Setlist\Domain\Entity\EventsTrigger;
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

        return Song::create($uuid, $title, $dateTime, $dateTime, $this->eventsTrigger);
    }

    public function restore(
        string $uuidString,
        string $title,
        string $formattedDateTime,
        string $formattedUpdateDate
    ): Song
    {
        $uuid = Uuid::create($uuidString);
        $creationDate = DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, $formattedDateTime);
        $updateDate = DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, $formattedUpdateDate);

        return Song::restore($uuid, $title, $creationDate, $updateDate, $this->eventsTrigger);
    }
}
