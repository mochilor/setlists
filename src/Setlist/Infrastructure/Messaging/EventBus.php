<?php

namespace Setlist\Infrastructure\Messaging;

use Setlist\Domain\Entity\DomainEvent;
use Setlist\Domain\Entity\EventBus as EventBusInterface;

class EventBus implements EventBusInterface
{
    private $handlers = [];

    public function addHandler(string $domainEventName, $handler)
    {
        $this->handlers[$domainEventName] = $handler;
    }

    public function handle(DomainEvent $domainEvent)
    {
        if (isset($this->handlers[get_class($domainEvent)])) {
            $eventHandler = $this->handlers[get_class($domainEvent)];

            return $eventHandler($domainEvent);
        }
    }
}
