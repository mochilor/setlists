<?php

namespace Tests\Unit\Setlist\Infrastructure\Messaging\Setlist;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasDeleted;
use Setlist\Infrastructure\Messaging\EventHandler\Setlist\SetlistWasDeletedHandler;

class SetlistWasDeletedHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function handlerCanBeInvoked()
    {
        $repository = $this->getMockBuilder(SetlistProjectorRepository::class)->getMock();
        $event = $this->getMockBuilder(SetlistWasDeleted::class)->disableOriginalConstructor()->getMock();
        $handler = new SetlistWasDeletedHandler($repository);

        $repository->expects($this->once())
            ->method('delete')
            ->with($event);

        ($handler)($event);
    }
}
