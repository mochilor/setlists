<?php

namespace Tests\Unit\Setlist\Application\Command\Handler;

use Setlist\Application\Command\CreateSong;
use Setlist\Application\Command\Handler\CreateSongHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Persistence\Song\ApplicationSongRepository;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;

class CreateSongHandlerTest extends TestCase
{
    private $applicationSongRepository;
    private $songRepository;
    private $commandHandler;
    private $songFactory;

    const ALL_TITLES = [
        'Title 1',
        'Title 2',
        'Title 3',
    ];

    protected function setUp()
    {
        $this->songRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->applicationSongRepository = $this->getMockBuilder(ApplicationSongRepository::class)->getMock();
        $eventsTrigger = new EventsTrigger();
        $this->songFactory = new SongFactory($eventsTrigger);
        $this->commandHandler = new CreateSongHandler(
            $this->songRepository,
            $this->applicationSongRepository,
            $this->songFactory
        );
    }

    /**
     * @test
     */
    public function commandHandlerCanBeInvoked()
    {
        $payload = [
            'title' => 'New Title'
        ];
        $command = new CreateSong($payload);
        $uuid = Uuid::random();

        $this->applicationSongRepository
            ->expects($this->once())
            ->method('getAllTitles')
            ->willReturn(self::ALL_TITLES);

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
            'title' => self::ALL_TITLES[0]
        ];
        $command = new CreateSong($payload);

        $this->applicationSongRepository
            ->expects($this->once())
            ->method('getAllTitles')
            ->willReturn(self::ALL_TITLES);

        ($this->commandHandler)($command);
    }
}
