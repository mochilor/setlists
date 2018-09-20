<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\ActFactory;
use Setlist\Domain\Entity\Setlist\SongCollection;
use Setlist\Domain\Entity\Song\Song;

class ActFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function factoryCanMakeInstances()
    {
        $songs = [
            $this->getSong(),
            $this->getSong(),
            $this->getSong(),
        ];
        $songCollection = SongCollection::create(...$songs);

        $factory = new ActFactory();

        $this->assertEquals(
            Act::create($songCollection),
            $factory->make($songs)
        );
    }

    protected function getSong()
    {
        return $this->getMockBuilder(Song::class)->getMock();
    }
}
