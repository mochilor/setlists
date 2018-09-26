<?php

namespace Tests\Unit\Setlist\Application\Command\Handler;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Handler\UpdateSongHandler;
use Setlist\Application\Command\UpdateSong;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;

class UpdateSongHandlerTest extends TestCase
{
    private $songRepository;
    private $commandHandler;

    protected function setUp()
    {
        $this->songRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->commandHandler = new UpdateSongHandler($this->songRepository);
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
     * @expectedException \Setlist\Domain\Exception\Song\SongDoesNotExistException
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
