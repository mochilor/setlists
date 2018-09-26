<?php

namespace Tests\Unit\Setlist\Application\Command;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\UpdateSong;

class UpdateSongTest extends TestCase
{
    const PAYLOAD = [
        'uuid' => '550e8400-e29b-41d4-a716-446655440000',
        'title' => 'A title',
    ];

    /**
     * @test
     */
    public function commandCanBeCreated()
    {
        $command = $this->getCommand();

        $this->assertInstanceOf(
            UpdateSong::class,
            $command
        );
    }

    private function getCommand()
    {
        return new UpdateSong(self::PAYLOAD);
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
    public function commandHasTitle()
    {
        $command = $this->getCommand();
        $this->assertEquals(
            self::PAYLOAD['title'],
            $command->title()
        );
    }
}
