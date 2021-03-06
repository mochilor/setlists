<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song\Event;

use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SongWasCreatedTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = $this->getMockBuilder(Uuid::class)->getMock();
        $title = 'A Title';
        $formattedDateTime = '2018-01-01 00:00:00';
        $event = SongWasCreated::create($uuid, $title, $formattedDateTime);

        $this->assertInstanceOf(
            SongWasCreated::class,
            $event
        );

        $this->assertEquals(
            $uuid,
            $event->id()
        );

        $this->assertEquals(
            $title,
            $event->title()
        );

        $this->assertEquals(
            true,
            $event->isVisible()
        );

        $this->assertEquals(
            $formattedDateTime,
            $event->formattedCreationDate()
        );

        $this->assertEquals(
            $formattedDateTime,
            $event->formattedUpdateDate()
        );
    }
}
