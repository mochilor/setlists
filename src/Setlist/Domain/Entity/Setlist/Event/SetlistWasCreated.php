<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\DomainEvent;
use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Value\Uuid;

class SetlistWasCreated implements DomainEvent
{
    private $id;
    private $actCollection;
    private $name;
    private $description;
    private $formattedDate;
    private $formattedCreationDate;
    private $formattedUpdateDate;

    public static function create(
        Uuid $id,
        ActCollection $actCollection,
        string $name,
        string $description,
        string $formattedDate,
        string $formattedCreationDate
    ): self
    {
        $event = new self();

        $event->id = $id;
        $event->actCollection = $actCollection;
        $event->name = $name;
        $event->description = $description;
        $event->formattedDate = $formattedDate;
        $event->formattedCreationDate = $formattedCreationDate;
        $event->formattedUpdateDate = $formattedCreationDate;

        return $event;
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

    public function description(): string
    {
        return $this->description;
    }

    public function formattedDate(): string
    {
        return $this->formattedDate;
    }

    public function formattedCreationDate(): string
    {
        return $this->formattedCreationDate;
    }

    public function formattedUpdateDate(): string
    {
        return $this->formattedUpdateDate;
    }
}
