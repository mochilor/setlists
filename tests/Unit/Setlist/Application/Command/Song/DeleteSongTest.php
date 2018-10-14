<?php

namespace Tests\Unit\Setlist\Application\Command\Song;

use Setlist\Application\Command\Song\DeleteSong;
use PHPUnit\Framework\TestCase;

class DeleteSongTest extends TestCase
{
    const PAYLOAD = [
        'uuid' => '550e8400-e29b-41d4-a716-446655440000',
    ];

    /**
     * @test
     */
    public function commandCanBeCreated()
    {
        $command = $this->getCommand();

        $this->assertInstanceOf(
            DeleteSong::class,
            $command
        );
    }

    private function getCommand()
    {
        return new DeleteSong(self::PAYLOAD);
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
