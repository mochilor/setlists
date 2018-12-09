<?php

namespace Tests\Unit\Setlist\Application\Query\Song\Handler;

use Setlist\Application\DataTransformer\SongDataTransformer;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollection;
use Setlist\Application\Persistence\Song\PersistedSongRepository;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Query\Song\GetSongsByTitle;
use Setlist\Application\Query\Song\Handler\GetSongsByTitleHandler;

class GetSongsByTitleHandlerTest extends TestCase
{
    private $getSongHandler;
    private $applicationSongRepository;
    private $songDataTransformer;

    protected function setUp()
    {
        $this->applicationSongRepository = $this->getMockBuilder(PersistedSongRepository::class)->getMock();
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

        $song = $this->getMockBuilder(PersistedSong::class)->disableOriginalConstructor()->getMock();
        $songCollection = PersistedSongCollection::create($song);
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
