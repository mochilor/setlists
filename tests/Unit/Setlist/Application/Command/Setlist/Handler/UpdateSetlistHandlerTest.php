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
use Setlist\Domain\Entity\Setlist\SetlistAvailabilityRepository;
use Setlist\Domain\Entity\Setlist\SetlistRepository;
use Setlist\Domain\Value\Uuid;
use Setlist\Infrastructure\Value\UuidGenerator;

class UpdateSetlistHandlerTest extends TestCase
{
    private $setlistNameRepository;
    private $setlistRepository;
    private $setlistHandlerHelper;
    private $commandHandler;
    private $uuidGenerator;

    const UUID_VALUE = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';

    protected function setUp()
    {
        $this->setlistNameRepository = $this->getMockBuilder(SetlistAvailabilityRepository::class)->getMock();
        $this->setlistRepository = $this->getMockBuilder(SetlistRepository::class)->getMock();
        $this->setlistHandlerHelper = $this->getMockBuilder(SetlistHandlerHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->uuidGenerator = $this->getMockBuilder(UuidGenerator::class)->getMock();

        $this->commandHandler = new UpdateSetlistHandler(
            $this->setlistRepository,
            $this->setlistNameRepository,
            $this->setlistHandlerHelper,
            $this->uuidGenerator
        );
    }

    /**
     * @test
     */
    public function commandHandlerCanBeInvoked()
    {
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();

        $payload = [
            'uuid' => $uuid,
            'name' => 'New Name',
            'description' => 'Description',
            'acts' => [
                [
                    '8ffd680a-ff57-41f3-ac5e-bf1d877f6951',
                    '8ffd680a-ff57-41f3-ac5e-bf1d877f6952',
                    '8ffd680a-ff57-41f3-ac5e-bf1d877f6953',
                    '8ffd680a-ff57-41f3-ac5e-bf1d877f6954',
                ],
                [
                    '8ffd680a-ff57-41f3-ac5e-bf1d877f6955',
                    '8ffd680a-ff57-41f3-ac5e-bf1d877f6956',
                    '8ffd680a-ff57-41f3-ac5e-bf1d877f6957',
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
        // $dateTime = DateTime::createFromFormat(Setlist::DATE_TIME_FORMAT, $command->date());

        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($payload['uuid'])
            ->willReturn($uuidObject);

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuidObject)
            ->willReturn($setlistMock);

        $this->setlistNameRepository
            ->expects($this->once())
            ->method('nameIsUnique')
            ->with($payload['name'], $payload['uuid'])
            ->willReturn(true);

        $this->setlistHandlerHelper
            ->expects($this->once())
            ->method('getActsForSetlist')
            ->with($command->acts())
            ->willReturn($actsForSetlist);

        $this->setlistRepository
            ->expects($this->once())
            ->method('save');

        $setlistMock
            ->expects($this->once())
            ->method('changeName')
            ->with($command->name());

        $setlistMock
            ->expects($this->once())
            ->method('changeDescription')
            ->with($command->description());

        $setlistMock
            ->expects($this->once())
            ->method('changeActCollection')
            ->with($actCollection);

        $setlistMock
            ->expects($this->once())
            ->method('changeDate')
            //->with($dateTime) // This can lead to mismatches when execution start on a second and ends on the next one!
        ;

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SetlistDoesNotExistException
     */
    public function notFoundSetlistThrowsException()
    {
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();

        $payload = [
            'uuid' => $uuid,
            'name' => 'New name',
            'description' => 'Description',
            'date' => '2018-10-01',
        ];
        $command = new UpdateSetlist($payload);

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($payload['uuid'])
            ->willReturn($uuidObject);

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuidObject)
            ->willReturn(null);

        ($this->commandHandler)($command);
    }

    /**
     * @test
     * @expectedException \Setlist\Application\Exception\SetlistNameNotUniqueException
     */
    public function repeatedTitleThrowsException()
    {
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();

        $payload = [
            'uuid' => $uuid,
            'name' => 'Non unique name',
            'description' => 'Description',
            'date' => '2018-10-01',
        ];
        $command = new UpdateSetlist($payload);

        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($payload['uuid'])
            ->willReturn($uuidObject);

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuidObject)
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
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();

        $payload = [
            'uuid' => $uuid,
            'name' => 'New name',
            'description' => 'Description',
            'date' => 'Invalid date!',
        ];
        $command = new UpdateSetlist($payload);

        $setlistMock = $this->getMockBuilder(Setlist::class)->getMock();

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($payload['uuid'])
            ->willReturn($uuidObject);

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuidObject)
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
        $uuid = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';
        $uuidObject = $this->getMockBuilder(Uuid::class)->getMock();

        $payload = [
            'uuid' => $uuid,
            'name' => 'New Name',
            'description' => 'Description',
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

        $this->uuidGenerator
            ->expects($this->once())
            ->method('fromString')
            ->with($payload['uuid'])
            ->willReturn($uuidObject);

        $this->setlistRepository
            ->expects($this->once())
            ->method('get')
            ->with($uuidObject)
            ->willReturn($setlistMock);

        $this->setlistNameRepository
            ->expects($this->once())
            ->method('nameIsUnique')
            ->with($command->name(), $command->uuid())
            ->willReturn(true);

        ($this->commandHandler)($command);
    }
}
