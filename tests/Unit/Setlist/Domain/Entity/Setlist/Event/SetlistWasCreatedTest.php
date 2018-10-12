<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist\Event;

use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SetlistWasCreatedTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        // Uuid $id, ActCollection $actCollection, string $name, string $formattedDate
        $uuid = Uuid::random();
        $actCollection = $this->getMockBuilder(ActCollection::class)->getMock();
        $name = 'A Name';
        $formattedDateTime = '2018-01-01';
        $event = SetlistWasCreated::create($uuid, $actCollection, $name, $formattedDateTime);

        $this->assertInstanceOf(
            SetlistWasCreated::class,
            $event
        );

        $this->assertEquals(
            $uuid,
            $event->id()
        );

        $this->assertEquals(
            $name,
            $event->name()
        );

        $this->assertEquals(
            $actCollection,
            $event->actCollection()
        );

        $this->assertEquals(
            $formattedDateTime,
            $event->formattedDate()
        );

        $this->assertInternalType(
            'int',
            $event->occurredOn()
        );
    }
}
