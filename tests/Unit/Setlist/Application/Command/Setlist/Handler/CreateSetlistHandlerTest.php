<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist\Handler;

use Setlist\Application\Command\Setlist\CreateSetlist;
use Setlist\Application\Command\Setlist\Handler\CreateSetlistHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Setlist\Handler\Helper\SetlistHandlerHelper;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Setlist\SetlistNameRepository;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Value\Uuid;

class CreateSetlistHandlerTest extends TestCase
{
    private $setlistNameRepository;
    private $setlistRepository;
    private $setlistFactory;
    private $setlistHandlerHelper;
    private $commandHandler;

    protected function setUp()
    {
        $this->setlistNameRepository = $this->getMockBuilder(SetlistNameRepository::class)->getMock();
        $this->setlistRepository = $this->getMockBuilder(SetlistRepository::class)->getMock();
        $this->setlistFactory = $this->getMockBuilder(SetlistFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->setlistHandlerHelper = $this->getMockBuilder(SetlistHandlerHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->commandHandler = new CreateSetlistHandler(
            $this->setlistNameRepository,
            $this->setlistRepository,
            $this->setlistFactory,
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
            'description' => 'Description',
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

        $actsForSetlist = [];
        foreach ($payload['acts'] as $act) {
            $actsForSetlist[] = $this->getMockBuilder(Act::class)->getMock();
        }

        $command = new CreateSetlist($payload);
        $uuid = Uuid::random();
        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();

        $this->setlistNameRepository
            ->expects($this->once())
            ->method('nameIsAvailable')
            ->with($command->name())
            ->willReturn(true);

        $this->setlistHandlerHelper
            ->expects($this->once())
            ->method('getActsForSetlist')
            ->willReturn($actsForSetlist);

        $this->setlistRepository
            ->expects($this->once())
            ->method('nextIdentity')
            ->willReturn($uuid);

        $this->setlistFactory
            ->expects($this->once())
            ->method('make')
            ->with($uuid, $actsForSetlist, $command->name(), $command->description(), $command->date())
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
            'name' => 'Non unique Name',
        ];
        $command = new CreateSetlist($payload);

        $this->setlistNameRepository
            ->expects($this->once())
            ->method('nameIsAvailable')
            ->with($command->name())
            ->willReturn(false);

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
            'description' => 'Description',
            'acts' => [
                [
                    $uuid,
                    $uuid,
                ],
            ],
        ];
        $command = new CreateSetlist($payload);

        $this->setlistNameRepository
            ->expects($this->once())
            ->method('nameIsAvailable')
            ->with($command->name())
            ->willReturn(true);

        ($this->commandHandler)($command);
    }
}
