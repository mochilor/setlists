<?php

namespace Tests\Unit\Setlist\Application\Persistence\Setlist;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Persistence\Song\PersistedSongCollection;

class PersistedSetlistTest extends TestCase
{
    /**
     * @test
     */
    public function persistedSetlistCanBeCreated()
    {
        $id = '550e8400-e29b-41d4-a716-446655440000';
        $persistedSetlistCollection = $this->getMockBuilder(PersistedSongCollection::class)->getMock();
        $acts = [
            $persistedSetlistCollection
        ];
        $name = 'Name';
        $description = 'Description';
        $date = '2018-01-04';
        $creationDate = '2018-01-01 10:00:00';
        $updateDate = '2018-01-01 10:00:00';

        $persistedSetlist = new PersistedSetlist($id, $acts, $name, $description, $date, $creationDate, $updateDate);

        $this->assertInstanceOf(
            PersistedSetlist::class,
            $persistedSetlist
        );

        $this->assertEquals(
            $id,
            $persistedSetlist->id()
        );

        $this->assertSame(
            $persistedSetlistCollection,
            $persistedSetlist->acts()[0]
        );

        $this->assertEquals(
            $name,
            $persistedSetlist->name()
        );

        $this->assertEquals(
            $description,
            $persistedSetlist->description()
        );

        $this->assertEquals(
            $date,
            $persistedSetlist->date()
        );

        $this->assertEquals(
            $creationDate,
            $persistedSetlist->creationDate()
        );

        $this->assertEquals(
            $updateDate,
            $persistedSetlist->updateDate()
        );
    }
}
