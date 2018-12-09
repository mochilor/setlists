<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsActCollection;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistChangedItsActCollectionHandler;
use PHPUnit\Framework\TestCase;

class SetlistChangedItsActCollectionHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $repository = $this->getMockBuilder(SetlistProjectorRepository::class)->getMock();
        $event = $this->getMockBuilder(SetlistChangedItsActCollection::class)->disableOriginalConstructor()->getMock();
        $handler = new SetlistChangedItsActCollectionHandler($repository);

        $repository->expects($this->once())
            ->method('changeActCollection')
            ->with($event);

        ($handler)($event);
    }
}
