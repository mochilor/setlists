<?php

namespace Setlist\Domain\Entity\Song\Event;

use Setlist\Domain\Entity\DomainEvent;
use Setlist\Domain\Value\Uuid;

class SongChangedItsTitle implements DomainEvent
{
    private $id;
    private $title;
    private $formattedUpdateDate;

    public static function create(Uuid $id, string $title, string $formattedUpdateDate): self
    {
        $event = new self();

        $event->id = $id;
        $event->title = $title;
        $event->formattedUpdateDate = $formattedUpdateDate;

        return $event;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function formattedUpdateDate(): string
    {
        return $this->formattedUpdateDate;
    }
}
