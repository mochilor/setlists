<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song\Event;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Value\Uuid;

class SongWasDeletedTest extends TestCase
{
    /**
     * @test
     */
    public function domainEventCanBeCreatedAndHasGetters()
    {
        $uuid = Uuid::random();
        $title = 'A Title';
        $event = SongWasDeleted::create($uuid);

        $this->assertInstanceOf(
            SongWasDeleted::class,
            $event
        );

        $this->assertInternalType(
            'int',
            $event->occurredOn()
        );
    }
}
