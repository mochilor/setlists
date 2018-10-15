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
        $formattedUpdateTime = '2018-01-01 00:00:00';
        $event = SongChangedItsTitle::create($uuid, $title, $formattedUpdateTime);

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
            'string',
            $event->formattedUpdateDate()
        );
    }
}
