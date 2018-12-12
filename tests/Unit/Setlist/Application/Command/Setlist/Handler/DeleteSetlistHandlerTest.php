<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist\Handler;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Setlist\Handler\DeleteSetlistHandler;
use Setlist\Application\Command\Setlist\DeleteSetlist;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Value\Uuid;
use Setlist\Infrastructure\Value\UuidGenerator;

class DeleteSetlistHandlerTest extends TestCase
{
    private $setlistRepository;
    private $commandHandler;
    private $uuidGenerator;

    const UUID_VALUE = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';

    protected function setUp()
    {
        $this->setlistRepository = $this->getMockBuilder(SetlistRepository::class)->getMock();
        $this->uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();
        $this->commandHandler = new DeleteSetlistHandler($this->setlistRepository, $this->uuidGenerator);
    }

    /**
     * @test
     */
    public function commandHandlerCanBeInvoked()
    {
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();

        $payload = [
            'uuid' => $uuid,
        ];
        $command = new DeleteSetlist($payload);

        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($payload['uuid'])
            ->willReturn($uuidObject);

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuidObject)
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
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();

        $payload = [
            'uuid' => $uuid,
        ];
        $command = new DeleteSetlist($payload);

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($payload['uuid'])
            ->willReturn($uuidObject);

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuidObject)
            ->willReturn(null);

        ($this->commandHandler)($command);
    }
}
