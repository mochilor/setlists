<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Value\Uuid;

class SetlistChangedItsDate
{
    private $id;
    private $formattedDate;
    private $occurredOn;
    private $formattedUpdateDate;

    public static function create(Uuid $id, string $formattedDate, string $formattedUpdateDate): self
    {
        $event = new self();

        $event->id = $id;
        $event->formattedDate = $formattedDate;
        $event->formattedUpdateDate = $formattedUpdateDate;

        return $event;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function formattedDate(): string
    {
        return $this->formattedDate;
    }

    public function formattedUpdateDate(): string
    {
        return $this->formattedUpdateDate;
    }
}
