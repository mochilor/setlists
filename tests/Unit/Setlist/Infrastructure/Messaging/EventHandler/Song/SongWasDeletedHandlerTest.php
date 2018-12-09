<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Song;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongWasDeletedHandler;

class SongWasDeletedHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $repository = $this->getMockBuilder(SetlistProjectorRepository::class)->getMock();
        $event = $this->getMockBuilder(SongWasDeleted::class)->disableOriginalConstructor()->getMock();
        $handler = new SongWasDeletedHandler($repository);

        $repository->expects($this->once())
            ->method('deleteSongInSetlists')
            ->with($event);

        ($handler)($event);
    }
}
