<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\DomainEvent;
use Setlist\Domain\Value\Uuid;

class SetlistWasDeleted implements DomainEvent
{
    private $id;

    public static function create(Uuid $id): self
    {
        $event = new self();
        $event->id = $id;

        return $event;
    }

    public function id(): Uuid
    {
        return $this->id;
    }
}
