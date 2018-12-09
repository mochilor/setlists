<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Song;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongWasCreatedHandler;

class SongWasCreatedHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $handler = new SongWasCreatedHandler();
        $event = $this->getMockBuilder(SongWasCreated::class)->disableOriginalConstructor()->getMock();

        ($handler)($event);

        $this->assertTrue(true);
    }
}
