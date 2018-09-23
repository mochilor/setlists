<?php

namespace Setlist\Domain\Entity;

trait TriggerEventsTrait
{
    private $events = [];

    protected function trigger($event)
    {
        $this->events[] = $event;
    }

    public function events(): array
    {
        return $this->events;
    }
}
