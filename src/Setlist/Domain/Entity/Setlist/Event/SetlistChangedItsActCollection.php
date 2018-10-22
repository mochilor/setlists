<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\DomainEvent;
use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Value\Uuid;

class SetlistChangedItsActCollection implements DomainEvent
{
    private $id;
    private $actCollection;
    private $formattedUpdateDate;

    public static function create(Uuid $id, ActCollection $actCollection, string $formattedUpdateDate): self
    {
        $event = new self();

        $event->id = $id;
        $event->actCollection = $actCollection;
        $event->formattedUpdateDate = $formattedUpdateDate;

        return $event;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function actCollection(): ActCollection
    {
        return $this->actCollection;
    }

    public function formattedUpdateDate(): string
    {
        return $this->formattedUpdateDate;
    }
}
