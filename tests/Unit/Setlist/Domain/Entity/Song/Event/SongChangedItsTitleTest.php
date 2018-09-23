<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song\Event;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Domain\Value\Uuid;

class SongChangedItsTitleTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = Uuid::random();
        $title = 'A Title';
        $event = SongChangedItsTitle::create($uuid, $title);

        $this->assertInstanceOf(
            SongChangedItsTitle::class,
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

        $this->assertInternalType(
            'int',
            $event->occurredOn()
        );
    }
}
