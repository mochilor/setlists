<?php

namespace Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Value\Uuid;

class SetlistChangedItsName
{
    private $id;
    private $name;
    private $formattedUpdateDate;

    public static function create(Uuid $id, string $name, string $formattedUpdateDate): self
    {
        $event = new self();

        $event->id = $id;
        $event->name = $name;
        $event->formattedUpdateDate = $formattedUpdateDate;

        return $event;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function formattedUpdateDate(): string
    {
        return $this->formattedUpdateDate;
    }
}
