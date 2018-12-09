<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTime;
use DateTimeImmutable;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Exception\Setlist\InvalidDateException;
use Setlist\Domain\Value\Uuid;

class SetlistFactory
{
    private $eventsTrigger;

    public function __construct(EventsTrigger $eventsTrigger)
    {
        $this->eventsTrigger = $eventsTrigger;
    }

    public function make(
        string $uuidString,
        array $acts,
        string $name,
        string $description,
        string $formattedDate
    ): Setlist
    {
        $uuid = Uuid::create($uuidString);
        $actCollection = ActCollection::create(...$acts);
        $date = $this->getDatetime($formattedDate);
        $creationDate = $updateDate = new DateTimeImmutable();

        return Setlist::create(
            $uuid,
            $actCollection,
            $name,
            $description,
            $date,
            $creationDate,
            $updateDate,
            $this->eventsTrigger
        );
    }

    public function restore(
        string $uuidString,
        array $acts,
        string $name,
        string $description,
        string $formattedDate,
        string $formattedCreationDate,
        string $formattedUpdateDate
    ): Setlist
    {
        $uuid = Uuid::create($uuidString);
        $actCollection = ActCollection::create(...$acts);
        $date = $this->getDatetime($formattedDate);
        $creationDate = DateTimeImmutable::createFromFormat(Setlist::CREATION_DATE_FORMAT, $formattedCreationDate);
        $updateDate = DateTimeImmutable::createFromFormat(Setlist::UPDATE_DATE_FORMAT, $formattedUpdateDate);

        return Setlist::restore(
            $uuid,
            $actCollection,
            $name,
            $description,
            $date,
            $creationDate,
            $updateDate,
            $this->eventsTrigger
        );
    }

    private function getDatetime(string $formattedDate)
    {
        $date = DateTime::createFromFormat(Setlist::DATE_TIME_FORMAT, $formattedDate);

        if (empty($date)) {
            throw new InvalidDateException('Invalid date provided');
        }

        return $date;
    }
}
