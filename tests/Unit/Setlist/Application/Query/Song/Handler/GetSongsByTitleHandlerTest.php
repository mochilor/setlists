<?php

namespace Tests\Unit\Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Persistence\Song\SongRepository;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Query\Song\GetSongsByTitle;
use Setlist\Application\Query\Song\Handler\GetSongsByTitleHandler;
use Setlist\Domain\Entity\Setlist\SongCollection;
use Setlist\Domain\Entity\Song\Song;

class GetSongsByTitleHandlerTest extends TestCase
{
    private $getSongHandler;
    private $applicationSongRepository;
    private $songDataTransformer;

    protected function setUp()
    {
        $this->applicationSongRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->songDataTransformer = $this->getMockBuilder(SongDataTransformer::class)->getMock();
        $this->getSongHandler = new GetSongsByTitleHandler($this->applicationSongRepository, $this->songDataTransformer);
    }

    /**
     * @test
     */
    public function queryHandlerCanBeInvoked()
    {
        $payload = [
            'title' => 'Song title',
        ];
        $query = new GetSongsByTitle($payload);

        $song = $this->getMockBuilder(Song::class)->getMock();
        $songCollection = SongCollection::create($song);
        $result = [];
        $this->applicationSongRepository
            ->expects($this->once())
            ->method('getSongsByTitle')
            ->with($payload['title'])
            ->willReturn($songCollection);

        $this->songDataTransformer
            ->expects($this->exactly(count($songCollection)))
            ->method('write')
            ->with($song);
        $this->songDataTransformer
            ->expects($this->exactly(count($songCollection)))
            ->method('read')
            ->willReturn($result);

        ($this->getSongHandler)($query);
    }
}
