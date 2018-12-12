<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTime;
use DateTimeImmutable;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Exception\Setlist\InvalidDateException;
use Setlist\Domain\Value\UuidGenerator;

class SetlistFactory
{
    private $eventsTrigger;
    private $uuidGenerator;

    public function __construct(EventsTrigger $eventsTrigger, UuidGenerator $uuidGenerator)
    {
        $this->eventsTrigger = $eventsTrigger;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function make(
        string $uuidString,
        array $acts,
        string $name,
        string $description,
        string $formattedDate
    ): Setlist
    {
        $uuid = $this->uuidGenerator->fromString($uuidString);
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
        $uuid = $this->uuidGenerator->fromString($uuidString);
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
