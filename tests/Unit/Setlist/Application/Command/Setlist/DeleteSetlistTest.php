<?php

namespace Tests\Unit\Setlist\Application\Command\Setlist;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Setlist\DeleteSetlist;

class DeleteSetlistTest extends TestCase
{
    const PAYLOAD = [
        'uuid' => '151c7306-604d-46b6-8099-0eb31b2b4af3',
    ];

    /**
     * @test
     */
    public function commandCanBeCreated()
    {
        $command = $this->getCommand();

        $this->assertInstanceOf(
            DeleteSetlist::class,
            $command
        );
    }

    private function getCommand()
    {
        return new DeleteSetlist(self::PAYLOAD);
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
}
