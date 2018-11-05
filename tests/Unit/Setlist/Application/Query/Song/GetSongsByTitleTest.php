<?php

namespace Tests\Unit\Setlist\Application\Query\Song;

use Setlist\Application\Query\Query;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Query\Song\GetSongsByTitle;

class GetSongsByTitleTest extends TestCase
{
    const PAYLOAD = [
        'title' => 'Song title',
    ];

    /**
     * @test
     */
    public function queryCanBeCreated()
    {
        $query = $this->getQuery();

        $this->assertInstanceOf(
            GetSongsByTitle::class,
            $query
        );
    }

    private function getQuery(): Query
    {
        return new GetSongsByTitle(self::PAYLOAD);
    }

    /**
     * @test
     */
    public function queryHasTitle()
    {
        $query = $this->getQuery();

        $this->assertEquals(
            self::PAYLOAD['title'],
            $query->title()
        );
    }
}
