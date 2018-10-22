<?php

namespace Setlist\Domain\Entity;

interface EventBus
{
    public function addHandler(string $domainEventName, $handler);
    public function handle(DomainEvent $domainEvent);
}