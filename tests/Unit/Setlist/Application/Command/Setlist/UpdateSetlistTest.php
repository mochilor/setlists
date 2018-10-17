<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Setlist\UpdateSetlist;

class UpdateSetlistTest extends TestCase
{
    const PAYLOAD = [
        'uuid' => '151c7306-604d-46b6-8099-0eb31b2b4af3',
        'name' => 'A name',
        'acts' => [],
        'date' => '2018-01-01',
    ];

    /**
     * @test
     */
    public function commandCanBeCreated()
    {
        $command = $this->getCommand();

        $this->assertInstanceOf(
            UpdateSetlist::class,
            $command
        );
    }

    private function getCommand()
    {
        return new UpdateSetlist(self::PAYLOAD);
    }

    /**
     * @test
     */
    public function commandHasUuid()
    {
        $command = $this->getCommand();
        $this->assertEquals(
            self::PAYLOAD['uuid'],
            $command->uuid()
        );
    }

    /**
     * @test
     */
    public function commandHasName()
    {
        $command = $this->getCommand();
        $this->assertEquals(
            self::PAYLOAD['name'],
            $command->name()
        );
    }

    /**
     * @test
     */
    public function commandHasActs()
    {
        $command = $this->getCommand();
        $this->assertEquals(
            self::PAYLOAD['acts'],
            $command->acts()
        );
    }

    /**
     * @test
     */
    public function commandHasDate()
    {
        $command = $this->getCommand();
        $this->assertEquals(
            self::PAYLOAD['date'],
            $command->date()
        );
    }
}
