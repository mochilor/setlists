<?php

namespace Tests\Unit\Setlist\Application\Command\Song\Handler;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Song\Handler\UpdateSongHandler;
use Setlist\Application\Command\Song\UpdateSong;
use Setlist\Application\Persistence\Song\SongRepository as ApplicationSongRespository;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Entity\Song\SongTitleRepository;
use Setlist\Domain\Value\Uuid;

class UpdateSongHandlerTest extends TestCase
{
    private $songRepository;
    private $commandHandler;
    private $songTitleRepository;

    protected function setUp()
    {
        $this->songRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->songTitleRepository = $this->getMockBuilder(SongTitleRepository::class)->getMock();
        $this->commandHandler = new UpdateSongHandler($this->songRepository, $this->songTitleRepository);
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

        $this->songTitleRepository
            ->expects($this->once())
            ->method('titleIsUnique')
            ->with($command->title(), $command->uuid())
            ->willReturn(true);

        $song->expects($this->once())
            ->method('changeTitle')
            ->with($command->title());

        $song->expects($this->once())
            ->method('changeVisibility')
            ->with($command->isVisible());

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

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SongTitleNotUniqueException
     */
    public function nonUniqueTitleThrowsException()
    {
        $command = $this->getCommand();
        $song = $this->getMockBuilder(Song::class)->getMock();
        $uuid = Uuid::create($command->uuid());

        $this->songRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn($song);

        $this->songTitleRepository
            ->expects($this->once())
            ->method('titleIsUnique')
            ->with($command->title(), $command->uuid())
            ->willReturn(false);

        ($this->commandHandler)($command);
    }

    private function getCommand(): UpdateSong
    {
        $payload = [
            'title' => 'New Title',
            'uuid' => '550e8400-e29b-41d4-a716-446655440000',
            'visibility' => true,
        ];
        $command = new UpdateSong($payload);

        return $command;
    }
}
