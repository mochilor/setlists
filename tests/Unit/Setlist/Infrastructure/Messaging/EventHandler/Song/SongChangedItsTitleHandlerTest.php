<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Song;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Infrastructure\Messaging\EventHandler\Song\SongChangedItsTitleHandler;
use PHPUnit\Framework\TestCase;

class SongChangedItsTitleHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $repository = $this->getMockBuilder(SetlistProjectorRepository::class)->getMock();
        $event = $this->getMockBuilder(SongChangedItsTitle::class)->disableOriginalConstructor()->getMock();
        $handler = new SongChangedItsTitleHandler($repository);

        $repository->expects($this->once())
            ->method('changeSongTitleInSetlists')
            ->with($event);

        ($handler)($event);
    }
}
