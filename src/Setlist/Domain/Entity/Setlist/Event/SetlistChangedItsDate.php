<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Value\Uuid;

class SetlistChangedItsDate
{
    private $id;
    private $date;
    private $occurredOn;

    public static function create(Uuid $id, \DateTime $date): self
    {
        $event = new self();

        $event->id = $id;
        $event->date = $date;
        $event->occurredOn = (new \DateTimeImmutable())->getTimestamp();

        return $event;
    }

    public function occurredOn(): int
    {
        return $this->occurredOn;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function date(): \DateTime
    {
        return $this->date;
    }
}
