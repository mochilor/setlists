<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist\Handler;

use Setlist\Application\Command\Setlist\CreateSetlist;
use Setlist\Application\Command\Setlist\Handler\CreateSetlistHandler;
use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Setlist\Handler\Helper\SetlistHandlerHelper;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Setlist\SetlistAvailabilityRepository;
use Setlist\Domain\Entity\Setlist\SetlistRepository;

class CreateSetlistHandlerTest extends TestCase
{
    private $setlistNameRepository;
    private $setlistRepository;
    private $setlistFactory;
    private $setlistHandlerHelper;
    private $commandHandler;

    const UUID_VALUE = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';

    protected function setUp()
    {
        $this->setlistNameRepository = $this->getMockBuilder(SetlistAvailabilityRepository::class)->getMock();
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
            'uuid' => self::UUID_VALUE,
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

        $actsForSetlist = [];
        foreach ($payload['acts'] as $act) {
            $actsForSetlist[] = $this->getMockBuilder(Act::class)->getMock();
        }

        $command = new CreateSetlist($payload);
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

        $this->setlistFactory
            ->expects($this->once())
            ->method('make')
            ->with($payload['uuid'], $actsForSetlist, $payload['name'], $payload['description'], $payload['date'])
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
            'uuid' => self::UUID_VALUE,
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
        $payload = [
            'uuid' => self::UUID_VALUE,
            'name' => 'New Name',
            'description' => 'Description',
            'acts' => [
                [
                    '8ffd680a-ff57-41f3-ac5e-bf1d877f6954',
                    '8ffd680a-ff57-41f3-ac5e-bf1d877f6954',
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
