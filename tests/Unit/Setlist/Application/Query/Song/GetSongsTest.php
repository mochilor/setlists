<?php

namespace Tests\Unit\Setlist\Application\Query\Song;

use Setlist\Application\Query\Query;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Query\Song\GetSongs;

class GetSongsTest extends TestCase
{
    const PAYLOAD = [
        'start' => '1',
        'length' => '9',
    ];

    /**
     * @test
     */
    public function queryCanBeCreated()
    {
        $query = $this->getQuery();

        $this->assertInstanceOf(
            GetSongs::class,
            $query
        );
    }

    private function getQuery(): Query
    {
        return new GetSongs(self::PAYLOAD);
    }

    /**
     * @test
     */
    public function queryHasStart()
    {
        $query = $this->getQuery();

        $this->assertEquals(
            self::PAYLOAD['start'],
            $query->start()
        );
    }

    /**
     * @test
     */
    public function queryHasLength()
    {
        $query = $this->getQuery();

        $this->assertEquals(
            self::PAYLOAD['length'],
            $query->length()
        );
    }
}
