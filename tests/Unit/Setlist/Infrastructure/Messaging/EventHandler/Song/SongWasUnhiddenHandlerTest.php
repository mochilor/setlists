<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Song;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Song\Event\SongWasUnhidden;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongWasUnhiddenHandler;

class SongWasUnhiddenHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $repository = $this->getMockBuilder(SetlistProjectorRepository::class)->getMock();
        $event = $this->getMockBuilder(SongWasUnhidden::class)->disableOriginalConstructor()->getMock();
        $handler = new SongWasUnhiddenHandler($repository);

        $repository->expects($this->once())
            ->method('unhideSongInSetlists')
            ->with($event);

        ($handler)($event);
    }
}
