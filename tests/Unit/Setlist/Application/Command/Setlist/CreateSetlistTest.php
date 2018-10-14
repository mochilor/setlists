<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Setlist\CreateSetlist;

class CreateSetlistTest extends TestCase
{
    const PAYLOAD = [
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
            CreateSetlist::class,
            $command
        );
    }

    private function getCommand()
    {
        return new CreateSetlist(self::PAYLOAD);
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
