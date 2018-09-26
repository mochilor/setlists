<?php

namespace Tests\Unit\Setlist\Application\Command;

use Setlist\Application\Command\CreateSong;
use PHPUnit\Framework\TestCase;

class CreateSongTest extends TestCase
{
    const PAYLOAD = [
        'title' => 'A title'
    ];

    /**
     * @test
     */
    public function commandCanBeCreated()
    {
        $command = $this->getCommand();

        $this->assertInstanceOf(
            CreateSong::class,
            $command
        );
    }

    private function getCommand()
    {
        return new CreateSong(self::PAYLOAD);
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
