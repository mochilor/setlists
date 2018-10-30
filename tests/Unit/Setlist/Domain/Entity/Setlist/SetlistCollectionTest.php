<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistCollection;

class SetlistCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function setlistCollectionCanBeCreated()
    {
        $acts = [
            $this->getAct(),
            $this->getAct(),
        ];

        $this->assertInstanceOf(
            SetlistCollection::class,
            SetlistCollection::create(...$acts)
        );
    }

    private function getAct()
    {
        return $this->getMockBuilder(Setlist::class)->getMock();
    }
}
