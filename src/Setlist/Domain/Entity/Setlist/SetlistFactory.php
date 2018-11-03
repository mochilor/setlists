<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTime;
use DateTimeImmutable;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Value\Uuid;

class SetlistFactory
{
    private $eventsTrigger;

    public function __construct(EventsTrigger $eventsTrigger)
    {
        $this->eventsTrigger = $eventsTrigger;
    }

    public function make(string $uuidString, array $acts, string $name, string $formattedDate): Setlist
    {
        $uuid = Uuid::create($uuidString);
        $actCollection = ActCollection::create(...$acts);
        $date = DateTime::createFromFormat(Setlist::DATE_TIME_FORMAT, $formattedDate);
        $creationDate = $updateDate = new DateTimeImmutable();

        return Setlist::create($uuid, $actCollection, $name, $date, $creationDate, $updateDate, $this->eventsTrigger);
    }

    public function restore(
        string $uuidString,
        array $acts,
        string $name,
        string $formattedDate,
        string $formattedCreationDate,
        string $formattedUpdateDate
    ): Setlist
    {
        $uuid = Uuid::create($uuidString);
        $actCollection = ActCollection::create(...$acts);
        $date = DateTime::createFromFormat(Setlist::DATE_TIME_FORMAT, $formattedDate);
        $creationDate = DateTimeImmutable::createFromFormat(Setlist::CREATION_DATE_FORMAT, $formattedCreationDate);
        $updateDate = DateTimeImmutable::createFromFormat(Setlist::UPDATE_DATE_FORMAT, $formattedUpdateDate);

        return Setlist::restore($uuid, $actCollection, $name, $date, $creationDate, $updateDate, $this->eventsTrigger);
    }
}
