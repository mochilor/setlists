<?php

namespace Setlist\Domain\Entity\Song\Event;

use Setlist\Domain\Value\Uuid;

class SongWasCreated
{
    private $id;
    private $title;
    private $occurredOn;
    private $formattedCreationDate;

    public static function create(Uuid $id, string $title, string $formattedCreationDate): self
    {
        $event = new self();

        $event->id = $id;
        $event->title = $title;
        $event->formattedCreationDate = $formattedCreationDate;
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

    public function title(): string
    {
        return $this->title;
    }

    public function formattedCreationDate(): string
    {
        return $this->formattedCreationDate;
    }
}
