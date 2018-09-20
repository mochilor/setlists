<?php

namespace Tests\Unit\Setlist\Domain\Entity\Song;

use DateTime;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Setlist\ActCollection;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Value\Uuid;

class SetlistFactoryTest extends TestCase
{
    /**
     * @test
     * public function make(Uuid $id, array $songs, string $name, DateTime $date): Setlist
     */
    public function factoryCanMakeInstances()
    {
        $uuid = Uuid::random();
        $songs = [
            $this->getAct(),
            $this->getAct(),
            $this->getAct(),
        ];
        $songCollection = ActCollection::create(...$songs);
        $name = 'Name';
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2017-08-30 00:00:00');

        $factory = new SetlistFactory();

        $this->assertEquals(
            Setlist::create($uuid, $songCollection, $name, $date),
            $factory->make($uuid, $songs, $name, $date)
        );
    }

    protected function getAct()
    {
        return $this->getMockBuilder(Act::class)->getMock();
    }
}
