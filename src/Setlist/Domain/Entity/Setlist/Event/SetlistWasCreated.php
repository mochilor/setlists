<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Value\Uuid;

class SetlistWasCreated
{
    private $id;
    private $actCollection;
    private $name;
    private $formattedDate;
    private $formattedCreationDate;
    private $formattedUpdateDate;

    public static function create(
        Uuid $id,
        ActCollection $actCollection,
        string $name,
        string $formattedDate,
        string $formattedCreationDate
    ): self
    {
        $event = new self();

        $event->id = $id;
        $event->actCollection = $actCollection;
        $event->name = $name;
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
