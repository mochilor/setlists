<?php

namespace Setlist\Domain\Entity;

class EventsTrigger
{
    private $eventBus;
    private $events;

    public function __construct(EventBus $eventBus)
    {
        $this->events = [];
        $this->eventBus = $eventBus;
    }

    public function trigger(DomainEvent $event)
    {
        $this->eventBus->handle($event);
        $this->events[] = $event;
    }

    public function events(): array
    {
        return $this->events;
    }
}
