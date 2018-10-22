<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist\Handler;

use Setlist\Application\Command\Setlist\CreateSetlist;
use Setlist\Application\Command\Setlist\Handler\CreateSetlistHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Setlist\Handler\Helper\SetlistHandlerHelper;
use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRespository;
use Setlist\Domain\Entity\EventBus;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Entity\Song\SongRepository;
use Setlist\Domain\Value\Uuid;

class CreateSetlistHandlerTest extends TestCase
{
    private $applicationSetlistRepository;
    private $setlistRepository;
    private $setlistFactory;
    private $songFactory;
    private $setlistHandlerHelper;
    private $songRepository;
    private $commandHandler;

    const ALL_NAMES = [
        'Name 1',
        'Name 2',
        'Name 3',
    ];

    protected function setUp()
    {
        $this->applicationSetlistRepository = $this->getMockBuilder(ApplicationSetlistRespository::class)->getMock();
        $this->setlistRepository = $this->getMockBuilder(SetlistRepository::class)->getMock();
        $this->songRepository = $this->getMockBuilder(SongRepository::class)->getMock();
        $this->setlistFactory = $this->getMockBuilder(SetlistFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);
        $this->songFactory = new SongFactory($eventsTrigger);
        $this->setlistHandlerHelper = $this->getMockBuilder(SetlistHandlerHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->commandHandler = new CreateSetlistHandler(
            $this->applicationSetlistRepository,
            $this->setlistRepository,
            $this->songRepository,
            $this->setlistFactory,
            $this->songFactory,
            $this->setlistHandlerHelper
        );
    }

    /**
     * @test
     */
    public function commandHandlerCanBeInvoked()
    {
        $payload = [
            'name' => 'New Name',
            'acts' => [
                [
                    Uuid::random()->uuid(),
                    Uuid::random()->uuid(),
                    Uuid::random()->uuid(),
                    Uuid::random()->uuid(),
                ],
                [
                    Uuid::random()->uuid(),
                    Uuid::random()->uuid(),
                    Uuid::random()->uuid(),
                ],
            ],
            'date' => '2018-10-01',
        ];

//        $songsCount = 0;
//        array_walk_recursive(
//            $payload['acts'],
//            function() use (&$songsCount) {
//                $songsCount++;
//            }
//        );

        $actsForSetlist = [];
        foreach ($payload['acts'] as $act) {
            $actsForSetlist[] = $this->getMockBuilder(Act::class)->getMock();
        }

        $command = new CreateSetlist($payload);
        $uuid = Uuid::random();
        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();

        $this->applicationSetlistRepository
            ->expects($this->once())
            ->method('getAllNames')
            ->willReturn(self::ALL_NAMES);

        $this->setlistHandlerHelper
            ->expects($this->once())
            ->method('getActsForSetlist')
            ->willReturn($actsForSetlist);

//        $this->songRepository
//            ->expects($this->exactly($songsCount))
//            ->method('get')
//            ->willReturn($this->getSongMock());

        $this->setlistRepository
            ->expects($this->once())
            ->method('nextIdentity')
            ->willReturn($uuid);

        $this->setlistFactory
            ->expects($this->once())
            ->method('make')
            ->with($uuid, $actsForSetlist, $command->name(), $command->date())
            ->willReturn($setlistMock);

        $this->setlistRepository
            ->expects($this->once())
            ->method('save')
            ->willReturn($setlistMock);

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SetlistNameNotUniqueException
     */
    public function repeatedTitleThrowsException()
    {
        $payload = [
            'name' => self::ALL_NAMES[0],
        ];
        $command = new CreateSetlist($payload);

        $this->applicationSetlistRepository
            ->expects($this->once())
            ->method('getAllNames')
            ->willReturn(self::ALL_NAMES);

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\InvalidSetlistException
     * @expectedExceptionMessage Non unique song provided
     */
    public function repeatedSongUuidThrowsException()
    {
        $uuid = Uuid::random()->uuid();
        $payload = [
            'name' => 'New Name',
            'acts' => [
                [
                    $uuid,
                    $uuid,
                ],
            ],
        ];
        $command = new CreateSetlist($payload);

        $this->applicationSetlistRepository
            ->expects($this->once())
            ->method('getAllNames')
            ->willReturn(self::ALL_NAMES);

        ($this->commandHandler)($command);
    }
}
