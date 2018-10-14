<?php

namespace Tests\Unit\Setlist\Application\Command\Song\Handler;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Song\Handler\UpdateSongHandler;
use Setlist\Application\Command\Song\UpdateSong;
use Setlist\Application\Persistence\Song\ApplicationSongRepository;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;

class UpdateSongHandlerTest extends TestCase
{
    private $songRepository;
    private $commandHandler;
    private $applicationSongRepository;

    protected function setUp()
    {
        $this->songRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->applicationSongRepository = $this->getMockBuilder(ApplicationSongRepository::class)->getMock();
        $this->commandHandler = new UpdateSongHandler($this->songRepository, $this->applicationSongRepository);
    }

    /**
     * @test
     */
    public function commandHandlerCanBeInvoked()
    {
        $command = $this->getCommand();
        $song = $this->getMockBuilder(Song::class)->getMock();
        $uuid = Uuid::create($command->uuid());

        $this->songRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn($song);

        $this->applicationSongRepository
            ->expects($this->once())
            ->method('getOtherTitles')
            ->with($command->uuid())
            ->willReturn([]);

        $song->expects($this->once())
            ->method('changeTitle')
            ->with($command->title());

        $this->songRepository
            ->expects($this->once())
            ->method('save');


        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SongDoesNotExistException
     */
    public function unknownUuidThrowsException()
    {
        $command = $this->getCommand();

        $this->songRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        ($this->commandHandler)($command);
    }

    private function getCommand(): UpdateSong
    {
        $payload = [
            'title' => 'New Title',
            'uuid' => '550e8400-e29b-41d4-a716-446655440000',
        ];
        $command = new UpdateSong($payload);

        return $command;
    }
}
