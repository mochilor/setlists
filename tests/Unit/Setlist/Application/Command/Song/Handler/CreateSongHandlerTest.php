<?php

namespace Tests\Unit\Setlist\Application\Command\Song\Handler;

use Setlist\Application\Command\Song\CreateSong;
use Setlist\Application\Command\Song\Handler\CreateSongHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\EventBus;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Entity\Song\SongTitleRepository;
use Setlist\Domain\Value\Uuid;

class CreateSongHandlerTest extends TestCase
{
    private $songTitleRepository;
    private $songRepository;
    private $commandHandler;
    private $songFactory;

    protected function setUp()
    {
        $this->songRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->songTitleRepository = $this->getMockBuilder(SongTitleRepository::class)->getMock();
        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $this->songFactory = new SongFactory($eventsTrigger);
        $this->commandHandler = new CreateSongHandler(
            $this->songTitleRepository,
            $this->songRepository,
            $this->songFactory
        );
    }

    /**
     * @test
     */
    public function commandHandlerCanBeInvoked()
    {
        $payload = [
            'title' => 'New Title',
        ];
        $command = new CreateSong($payload);
        $uuid = Uuid::random();

        $this->songTitleRepository
            ->expects($this->once())
            ->method('titleIsAvailable')
            ->with($command->title())
            ->willReturn(true);

        $this->songRepository
            ->expects($this->once())
            ->method('nextIdentity')
            ->willReturn($uuid);

        $this->songRepository
            ->expects($this->once())
            ->method('save');

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SongTitleNotUniqueException
     */
    public function repeatedTitleThrowsException()
    {
        $payload = [
            'title' => 'Non unique title',
        ];
        $command = new CreateSong($payload);

        $this->songTitleRepository
            ->expects($this->once())
            ->method('titleIsAvailable')
            ->with($command->title())
            ->willReturn(false);

        ($this->commandHandler)($command);
    }
}
