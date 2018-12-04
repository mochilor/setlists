<?php

namespace Tests\Unit\Setlist\Application\Persistence\Setlist;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistCollection;
use PHPUnit\Framework\TestCase;

class PersistedSetlistCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function persistedSetlistCollectionCanBeCreated()
    {
        $persistedSetlist = $this->getMockBuilder(PersistedSetlist::class)
            ->disableOriginalConstructor()
            ->getMock();

        $persistedSetlistCollection = PersistedSetlistCollection::create($persistedSetlist);

        $this->assertInstanceOf(
            PersistedSetlistCollection::class,
            $persistedSetlistCollection
        );

        $this->assertSame(
            $persistedSetlist,
            $persistedSetlistCollection[0]
        );
    }
}
