<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Song;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Song\Event\SongWasHidden;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongWasHiddenHandler;

class SongWasHiddenHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $repository = $this->getMockBuilder(SetlistProjectorRepository::class)->getMock();
        $event = $this->getMockBuilder(SongWasHidden::class)->disableOriginalConstructor()->getMock();
        $handler = new SongWasHiddenHandler($repository);

        $repository->expects($this->once())
            ->method('hideSongInSetlists')
            ->with($event);

        ($handler)($event);
    }
}
