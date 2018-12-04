<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song\Event;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\Event\SongWasUnhidden;
use Setlist\Domain\Value\Uuid;

class SongWasUnhiddenTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = Uuid::random();
        $formattedUpdateTime = '2018-01-01 00:00:00';
        $event = SongWasUnhidden::create($uuid, $formattedUpdateTime);

        $this->assertInstanceOf(
            SongWasUnhidden::class,
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
