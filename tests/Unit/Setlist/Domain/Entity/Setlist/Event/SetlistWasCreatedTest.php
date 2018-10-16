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
        $uuid = Uuid::random();
        $actCollection = $this->getMockBuilder(ActCollection::class)->getMock();
        $name = 'A Name';
        $formattedDateTime = $formattedUpdateDate = '2018-01-01';
        $event = SetlistWasCreated::create($uuid, $actCollection, $name, $formattedDateTime, $formattedUpdateDate);

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

        $this->assertEquals(
            $formattedUpdateDate,
            $event->formattedUpdateDate()
        );

        $this->assertEquals(
            $formattedDateTime,
            $event->formattedCreationDate()
        );
    }
}
