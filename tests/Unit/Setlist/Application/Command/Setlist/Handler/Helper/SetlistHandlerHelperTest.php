<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist\Handler\Helper;

use Setlist\Application\Command\Setlist\Handler\Helper\SetlistHandlerHelper;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\ActFactory;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;
use Setlist\Infrastructure\Value\UuidGenerator;

class SetlistHandlerHelperTest extends TestCase
{
    private $songRepository;
    private $actFactory;
    private $setlistHandlerHelper;
    private $uuidGenerator;

    public function setUp()
    {
        $this->songRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->actFactory = $this->getMockBuilder(ActFactory::class)->getMock();
        $this->uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();

        $this->setlistHandlerHelper = new SetlistHandlerHelper(
            $this->songRepository,
            $this->actFactory,
            $this->uuidGenerator
        );
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
                $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
                $this->uuidGenerator
                    ->expects($this->at($n))
                    ->method('fromString')
                    ->with($songUuid)
                    ->willReturn($uuidObject);
                $this->songRepository
                    ->expects($this->at($n))
                    ->method('get')
                    ->with($uuidObject)
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
        $uuid ='c88e9355-939d-4160-9570-97560b475710';
        $acts = [
            [
                $uuid,
            ],
        ];

        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();
        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($uuid)
            ->willReturn($uuidObject);

        $this->songRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuidObject)
            ->willReturn(null);

        $this->setlistHandlerHelper->getActsForSetlist($acts);
    }
}
