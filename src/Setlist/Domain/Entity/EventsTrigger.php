<?php

namespace Setlist\Domain\Entity;

class EventsTrigger
{
    private $events = [];

    public function trigger($event)
    {
        $this->events[] = $event;
    }

    public function events(): array
    {
        return $this->events;
    }
}
