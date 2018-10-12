<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Value\Uuid;

class SetlistChangedItsName
{
    private $id;
    private $name;
    private $occurredOn;

    public static function create(Uuid $id, string $name): self
    {
        $event = new self();

        $event->id = $id;
        $event->name = $name;
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

    public function name(): string
    {
        return $this->name;
    }
}
