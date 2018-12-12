<?php

namespace Setlist\Domain\Entity\Song;

use DateTimeImmutable;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Value\UuidGenerator;

class SongFactory
{
    private $eventsTrigger;
    private $uuidGenerator;

    public function __construct(EventsTrigger $eventsTrigger, UuidGenerator $uuidGenerator)
    {
        $this->eventsTrigger = $eventsTrigger;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function make(string $uuidString, string $title): Song
    {
        $uuid = $this->uuidGenerator->fromString($uuidString);
        $dateTime = new DateTimeImmutable();

        return Song::create($uuid, $title, $dateTime, $dateTime, $this->eventsTrigger);
    }

    public function restore(
        string $uuidString,
        string $title,
        bool $isVisible,
        string $formattedDateTime,
        string $formattedUpdateDate
    ): Song
    {
        $uuid = $this->uuidGenerator->fromString($uuidString);
        $creationDate = DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, $formattedDateTime);
        $updateDate = DateTimeImmutable::createFromFormat(Song::CREATION_DATE_FORMAT, $formattedUpdateDate);

        return Song::restore($uuid, $title, $isVisible, $creationDate, $updateDate, $this->eventsTrigger);
    }
}
