<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging;

use Setlist\Domain\Entity\DomainEvent;
use Setlist\Infrastructure\Messaging\EventBus;
use PHPUnit\Framework\TestCase;

class EventBusTest extends TestCase
{
    private $eventBus;

    public function setUp()
    {
        $this->eventBus = new EventBus();
    }

    /**
     * @test
     */
    public function eventBusCanHandleEvents()
    {
        $this->eventBus->addHandler(DummyEvent::class, new DummyEventHandler());

        $this->assertEquals(
            DummyEventHandler::$message,
            $this->eventBus->handle(new DummyEvent())
        );
    }

    /**
     * @test
     */
    public function eventWithNoHandlerRetunrsNothing()
    {
        $this->assertEquals(
            null,
            $this->eventBus->handle(new DummyEvent())
        );
    }
}

class DummyEvent implements DomainEvent
{
}

class DummyEventHandler
{
    public static $message = 'Invoked!';

    public function __invoke()
    {
        return self::$message;
    }
}
