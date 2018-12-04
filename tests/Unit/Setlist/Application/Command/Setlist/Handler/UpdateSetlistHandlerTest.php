<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist\Handler;

use DateTime;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Setlist\Handler\Helper\SetlistHandlerHelper;
use Setlist\Application\Command\Setlist\Handler\UpdateSetlistHandler;
use Setlist\Application\Command\Setlist\UpdateSetlist;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistNameRepository;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Value\Uuid;

class UpdateSetlistHandlerTest extends TestCase
{
    private $setlistNameRepository;
    private $setlistRepository;
    private $setlistHandlerHelper;
    private $commandHandler;

    protected function setUp()
    {
        $this->setlistNameRepository = $this->getMockBuilder(SetlistNameRepository::class)->getMock();
        $this->setlistRepository = $this->getMockBuilder(SetlistRepository::class)->getMock();
        $this->setlistHandlerHelper = $this->getMockBuilder(SetlistHandlerHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->commandHandler = new UpdateSetlistHandler(
            $this->setlistRepository,
            $this->setlistNameRepository,
            $this->setlistHandlerHelper
        );
    }

    /**
     * @test
     */
    public function commandHandlerCanBeInvoked()
    {
        $uuid = Uuid::random();
        $payload = [
            'uuid' => $uuid->uuid(),
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
        $command = new UpdateSetlist($payload);

        $actsForSetlist = [];
        foreach ($payload['acts'] as $act) {
            $actsForSetlist[] = $this->getMockBuilder(Act::class)->getMock();
        }
        $actCollection = ActCollection::create(...$actsForSetlist);
        $dateTime = DateTime::createFromFormat(Setlist::DATE_TIME_FORMAT, $command->date());

        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();

        $this->setlistNameRepository
            ->expects($this->once())
            ->method('nameIsUnique')
            ->with($command->name(), $command->uuid())
            ->willReturn(true);

        $this->setlistHandlerHelper
            ->expects($this->once())
            ->method('getActsForSetlist')
            ->with($command->acts())
            ->willReturn($actsForSetlist);

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn($setlistMock);

        $this->setlistRepository
            ->expects($this->once())
            ->method('save');

        $setlistMock
            ->expects($this->once())
            ->method('changeName')
            ->with($command->name());

        $setlistMock
            ->expects($this->once())
            ->method('changeActCollection')
            ->with($actCollection);

        $setlistMock
            ->expects($this->once())
            ->method('changeDate')
            ->with($dateTime);

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SetlistDoesNotExistException
     */
    public function notFoundSetlistThrowsException()
    {
        $uuid = Uuid::random();
        $payload = [
            'uuid' => $uuid->uuid(),
            'name' => 'New name',
            'date' => '2018-10-01',
        ];
        $command = new UpdateSetlist($payload);

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn(null);

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SetlistNameNotUniqueException
     */
    public function repeatedTitleThrowsException()
    {
        $uuid = Uuid::random();
        $payload = [
            'uuid' => $uuid->uuid(),
            'name' => 'Non unique name',
            'date' => '2018-10-01',
        ];
        $command = new UpdateSetlist($payload);

        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();
        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn($setlistMock);

        $this->setlistNameRepository
            ->expects($this->once())
            ->method('nameIsUnique')
            ->with($command->name(), $command->uuid())
            ->willReturn(false);

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Domain\Exception\Setlist\InvalidDateException
     */
    public function InvalidDateThrowsException()
    {
        $uuid = Uuid::random();
        $payload = [
            'uuid' => $uuid->uuid(),
            'name' => 'New name',
            'date' => 'Invalid date!',
        ];
        $command = new UpdateSetlist($payload);

        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();
        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn($setlistMock);

        $this->setlistNameRepository
            ->expects($this->once())
            ->method('nameIsUnique')
            ->with($command->name(), $command->uuid())
            ->willReturn(true);

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\InvalidSetlistException
     * @expectedExceptionMessage Non unique song provided
     */
    public function repeatedSongUuidThrowsException()
    {
        $uuid = Uuid::random();
        $payload = [
            'uuid' => $uuid->uuid(),
            'name' => 'New Name',
            'date' => '2018-10-01',
            'acts' => [
                [
                    $uuid,
                    $uuid,
                ],
            ],
        ];
        $command = new UpdateSetlist($payload);

        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();
        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuid)
            ->willReturn($setlistMock);

        $this->setlistNameRepository
            ->expects($this->once())
            ->method('nameIsUnique')
            ->with($command->name(), $command->uuid())
            ->willReturn(true);

        ($this->commandHandler)($command);
    }
}
