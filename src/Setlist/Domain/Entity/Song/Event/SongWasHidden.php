<?php

namespace Setlist\Domain\Entity\Song\Event;

use Setlist\Domain\Entity\DomainEvent;
use Setlist\Domain\Value\Uuid;

class SongWasHidden implements DomainEvent
{
    private $id;
    private $formattedUpdateDate;

    public static function create(Uuid $id, string $formattedUpdateDate): self
    {
        $event = new self();

        $event->id = $id;
        $event->formattedUpdateDate = $formattedUpdateDate;

        return $event;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function formattedUpdateDate(): string
    {
        return $this->formattedUpdateDate;
    }
}
