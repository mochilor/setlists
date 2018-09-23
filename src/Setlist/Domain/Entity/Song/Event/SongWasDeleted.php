<?php

namespace Setlist\Domain\Entity\Song\Event;

use Setlist\Domain\Value\Uuid;

class SongWasDeleted
{
    private $id;
    private $occurredOn;

    public static function create(Uuid $id): self
    {
        $event = new self();

        $event->id = $id;
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
}
