<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTime;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use Setlist\Domain\Value\Uuid;

class SetlistFactory
{
    private $eventsTrigger;

    public function __construct(EventsTrigger $eventsTrigger)
    {
        $this->eventsTrigger = $eventsTrigger;
    }

    public function make(string $uuidString, array $acts, string $name): Setlist
    {
        $uuid = Uuid::create($uuidString);
        $actCollection = ActCollection::create(...$acts);
        $dateTime = new DateTime();
        $this->eventsTrigger->trigger(
            SetlistWasCreated::create($uuid, $actCollection, $name, $dateTime->format(Setlist::DATE_TIME_FORMAT))
        );

        return Setlist::create($uuid, $actCollection, $name, $dateTime, $this->eventsTrigger);
    }

    public function restore(string $uuidString, array $acts, string $name, string $formattedDateTime): Setlist
    {
        $uuid = Uuid::create($uuidString);
        $actCollection = ActCollection::create(...$acts);
        $dateTime = DateTime::createFromFormat(Setlist::DATE_TIME_FORMAT, $formattedDateTime);

        return Setlist::create($uuid, $actCollection, $name, $dateTime, $this->eventsTrigger);
    }
}
