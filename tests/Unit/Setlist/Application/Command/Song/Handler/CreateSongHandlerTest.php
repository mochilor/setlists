<?php

namespace Tests\Unit\Setlist\Application\Command\Song\Handler;

use Setlist\Application\Command\Song\CreateSong;
use Setlist\Application\Command\Song\Handler\CreateSongHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Entity\Song\SongTitleRepository;

class CreateSongHandlerTest extends TestCase
{
    private $songTitleRepository;
    private $songRepository;
    private $commandHandler;
    private $songFactory;

    const UUID_VALUE = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';

    protected function setUp()
    {
        $this->songTitleRepository = $this->getMockBuilder(SongTitleRepository::class)->getMock();
        $this->songRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->songFactory = $this->getMockBuilder(SongFactory::class)->disableOriginalConstructor()->getMock();

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
            'uuid' => self::UUID_VALUE,
            'title' => 'New Title',
        ];
        $command = new CreateSong($payload);

        $this->songTitleRepository
            ->expects($this->once())
            ->method('titleIsAvailable')
            ->with($command->title())
            ->willReturn(true);

        $this->songFactory
            ->expects($this->once())
            ->method('make')
            ->with($payload['uuid'], $payload['title']);

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
            'uuid' => self::UUID_VALUE,
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
