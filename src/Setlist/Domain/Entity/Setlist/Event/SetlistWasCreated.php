<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Value\Uuid;

class SetlistWasCreated
{
    private $id;
    private $actCollection;
    private $name;
    private $occurredOn;
    private $formattedDate;

    public static function create(Uuid $id, ActCollection $actCollection, string $name, string $formattedDate): self
    {
        $event = new self();

        $event->id = $id;
        $event->actCollection = $actCollection;
        $event->name = $name;
        $event->formattedDate = $formattedDate;
        $event->occurredOn = (new \DateTimeImmutable())->getTimestamp();

        return $event;
    }

    public function occurredOn(): int
    {
        return $this->occurredOn;
    }

    public function actCollection(): ActCollection
    {
        return $this->actCollection;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function formattedDate(): string
    {
        return $this->formattedDate;
    }
}
