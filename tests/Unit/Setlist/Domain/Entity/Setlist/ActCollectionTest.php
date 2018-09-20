<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist;

use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\ActCollection;

class ActCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function actCollectionCanBeCreated()
    {
        $acts = [
            $this->getAct(),
            $this->getAct(),
        ];

        $this->assertInstanceOf(
            ActCollection::class,
            ActCollection::create(...$acts)
        );
    }

    private function getAct()
    {
        return $this->getMockBuilder(Act::class)->getMock();
    }
}
