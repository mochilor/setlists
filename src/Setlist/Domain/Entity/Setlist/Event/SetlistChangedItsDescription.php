<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\DomainEvent;
use Setlist\Domain\Value\Uuid;

class SetlistChangedItsDescription implements DomainEvent
{
    private $id;
    private $description;
    private $formattedUpdateDate;

    public static function create(Uuid $id, string $description, string $formattedUpdateDate): self
    {
        $event = new self();

        $event->id = $id;
        $event->description = $description;
        $event->formattedUpdateDate = $formattedUpdateDate;

        return $event;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function formattedUpdateDate(): string
    {
        return $this->formattedUpdateDate;
    }
}
