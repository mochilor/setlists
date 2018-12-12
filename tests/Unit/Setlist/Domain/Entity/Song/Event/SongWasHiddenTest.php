<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song\Event;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\Event\SongWasHidden;
use Setlist\Domain\Value\Uuid;

class SongWasHiddenTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = $this->getMockBuilder(Uuid::class)->getMock();
        $formattedUpdateTime = '2018-01-01 00:00:00';
        $event = SongWasHidden::create($uuid, $formattedUpdateTime);

        $this->assertInstanceOf(
            SongWasHidden::class,
            $event
        );

        $this->assertEquals(
            $uuid,
            $event->id()
        );

        $this->assertEquals(
            $formattedUpdateTime,
            $event->formattedUpdateDate()
        );

        $this->assertEquals(
            $formattedUpdateTime,
            $event->formattedUpdateDate()
        );
    }
}
