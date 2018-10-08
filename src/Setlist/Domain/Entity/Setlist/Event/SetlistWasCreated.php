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
    private $formattedDateTime;

    public static function create(Uuid $id, ActCollection $actCollection, string $name, string $formattedDateTime): self
    {
        $event = new self();

        $event->id = $id;
        $event->actCollection = $actCollection;
        $event->name = $name;
        $event->formattedDateTime = $formattedDateTime;
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

    public function formattedDateTime(): string
    {
        return $this->formattedDateTime;
    }
}
