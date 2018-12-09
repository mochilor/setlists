<?php

namespace Tests\Unit\Setlist\Application\Command\Song;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\Song\ForceDeleteSong;

class ForceDeleteSongTest extends TestCase
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
            ForceDeleteSong::class,
            $command
        );
    }

    private function getCommand()
    {
        return new ForceDeleteSong(self::PAYLOAD);
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
