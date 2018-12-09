<?php

namespace Tests\Unit\Setlist\Application\Command\Song\Handler;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Song\DeleteSong;
use Setlist\Application\Command\Song\Handler\DeleteSongHandler;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepository;

class DeleteSongHandlerTest extends TestCase
{
    private $songRepository;
    private $commandHandler;
    private $applicationSetlistRepository;

    protected function setUp()
    {
        $this->songRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->applicationSetlistRepository = $this->getMockBuilder(ApplicationSetlistRepository::class)->getMock();
        $this->commandHandler = new DeleteSongHandler($this->songRepository, $this->applicationSetlistRepository);
    }

    /**
     * @test
     */
    public function commandHandlerCanBeInvoked()
    {
        $command = $this->getCommand();
        $song = $this->getMockBuilder(Song::class)->getMock();

        $this->songRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn($song);

        $this->applicationSetlistRepository
            ->expects($this->once())
            ->method('getSelistsCountBySongId')
            ->willReturn(0);

        $song->expects($this->once())
            ->method('delete');

        $this->songRepository
            ->expects($this->once())
            ->method('save');

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SongDoesNotExistException
     */
    public function songPresentInSetlistThrowsException()
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
     * @expectedException \Setlist\Application\Exception\SongCanNotBeDeletedException
     */
    public function unknownUuidThrowsException()
    {
        $command = $this->getCommand();
        $song = $this->getMockBuilder(Song::class)->getMock();

        $this->songRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn($song);

        $this->applicationSetlistRepository
            ->expects($this->once())
            ->method('getSelistsCountBySongId')
            ->willReturn(1);

        ($this->commandHandler)($command);
    }

    private function getCommand(): DeleteSong
    {
        $payload = [
            'uuid' => '550e8400-e29b-41d4-a716-446655440000'
        ];

        return new DeleteSong($payload);
    }
}
