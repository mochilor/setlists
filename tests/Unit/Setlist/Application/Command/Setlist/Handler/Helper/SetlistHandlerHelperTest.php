<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist\Handler\Helper;

use Setlist\Application\Command\Setlist\Handler\Helper\SetlistHandlerHelper;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\ActFactory;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;

class SetlistHandlerHelperTest extends TestCase
{
    private $songRepository;
    private $actFactory;
    private $setlistHandlerHelper;

    public function setUp()
    {
        $this->songRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->actFactory = $this->getMockBuilder(ActFactory::class)->getMock();

        $this->setlistHandlerHelper = new SetlistHandlerHelper($this->songRepository, $this->actFactory);
    }

    /**
     * @test
     * @dataProvider createActsForSetlistDataProvider
     */
    public function helperCanCreateActsForSetlist($acts, $actsResult, $message)
    {
        ksort($acts);
        $acts = array_values($acts);

        $n = 0;
        foreach ($acts as $keyAct => $act) {
            $songs = [];
            foreach ($act as $keySong => $songUuid) {
                $song = $this->getMockBuilder(Song::class)->getMock();
                $this->songRepository
                    ->expects($this->at($n))
                    ->method('get')
                    ->with($songUuid)
                    ->willReturn($song);

                $songs[] = $song;
                $n++;
            }

            $this->actFactory
                ->expects($this->at($keyAct))
                ->method('make')
                ->with($songs)
                ->willReturn($this->getActMock());
        }

        $this->assertEquals(
            $actsResult,
            $this->setlistHandlerHelper->getActsForSetlist($acts),
            $message
        );
    }

    public function createActsForSetlistDataProvider()
    {
        $orderedActs = [
            [
                'c88e9355-939d-4160-9570-97560b475710',
                'c88e9355-939d-4160-9570-97560b475711',
            ],
            [
                'c88e9355-939d-4160-9570-97560b475712',
                'c88e9355-939d-4160-9570-97560b475713',
            ],
        ];

        $disorderedActs = [
            9 => [
                'c88e9355-939d-4160-9570-97560b475712',
                'c88e9355-939d-4160-9570-97560b475713',
                'c88e9355-939d-4160-9570-97560b475714',
            ],
            1 => [
                'c88e9355-939d-4160-9570-97560b475710',
                'c88e9355-939d-4160-9570-97560b475711',
            ],
            10 => [
                'c88e9355-939d-4160-9570-97560b475715',
                'c88e9355-939d-4160-9570-97560b475716',
            ],
        ];
        return [
            [
                $orderedActs,
                [
                    $this->getActMock(),
                    $this->getActMock(),
                ],
                'Testing a well sorted act array'
            ],
            [
                $disorderedActs,
                [
                    $this->getActMock(),
                    $this->getActMock(),
                    $this->getActMock(),
                ],
                'Testing a bad sorted act array'
            ],
        ];
    }

    private function getActMock()
    {
        return $this->getMockBuilder(Act::class)->getMock();
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\InvalidSetlistException
     */
    public function invalidSongThrowsException()
    {
        $acts = [
            [
                'c88e9355-939d-4160-9570-97560b475710',
            ],
        ];

        $this->songRepository
            ->expects($this->once())
            ->method('get')
            ->with($acts[0][0])
            ->willReturn(null);

        $this->setlistHandlerHelper->getActsForSetlist($acts);
    }
}
