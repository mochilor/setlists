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
        'title' => 'Hello!',
        'notIn' => '8ffd680a-ff57-41f3-ac5e-bf1d877f6950',
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

    /**
     * @test
     */
    public function queryHasNotIn()
    {
        $query = $this->getQuery();

        $this->assertEquals(
            self::PAYLOAD['notIn'],
            $query->notIn()
        );
    }
}
