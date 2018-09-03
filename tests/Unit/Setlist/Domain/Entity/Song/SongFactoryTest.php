<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song;

use Setlist\Domain\Entity\Song\Song;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Value\Uuid;

class SongFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function factoryCanMakeInstances()
    {
        $uuid = Uuid::random();
        $title = 'Title';
        $factory = new SongFactory();

        $this->assertEquals(
            Song::create($uuid, $title),
            $factory->make($uuid, $title)
        );
    }
}
