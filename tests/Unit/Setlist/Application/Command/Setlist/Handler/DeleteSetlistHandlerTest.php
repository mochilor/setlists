<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist\Handler;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Setlist\Handler\DeleteSetlistHandler;
use Setlist\Application\Command\Setlist\DeleteSetlist;
use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRespository;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Value\Uuid;

class DeleteSetlistHandlerTest extends TestCase
{
    private $applicationSetlistRepository;
    private $setlistRepository;
    private $commandHandler;

    protected function setUp()
    {
        $this->applicationSetlistRepository = $this->getMockBuilder(ApplicationSetlistRespository::class)->getMock();
        $this->setlistRepository = $this->getMockBuilder(SetlistRepository::class)->getMock();
        $this->commandHandler = new DeleteSetlistHandler(
            $this->setlistRepository,
            $this->applicationSetlistRepository
        );
    }

    /**
     * @test
     */
    public function commandHandlerCanBeInvoked()
    {
        $uuid = Uuid::random();
        $payload = [
            'uuid' => $uuid->uuid(),
        ];
        $command = new DeleteSetlist($payload);

        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn($setlistMock);

        $this->setlistRepository
            ->expects($this->once())
            ->method('save');

        $setlistMock
            ->expects($this->once())
            ->method('delete');

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SetlistDoesNotExistException
     */
    public function notFoundSetlistThrowsException()
    {
        $uuid = Uuid::random();
        $payload = [
            'uuid' => $uuid->uuid(),
        ];
        $command = new DeleteSetlist($payload);

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn(null);

        ($this->commandHandler)($command);
    }
}
