<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Value\Uuid;

class SetlistWasDeleted
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
