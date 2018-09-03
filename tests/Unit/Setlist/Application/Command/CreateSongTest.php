<?php

namespace Tests\Unit\Setlist\Application\Command;

use Setlist\Application\Command\CreateSong;
use PHPUnit\Framework\TestCase;

class CreateSongTest extends TestCase
{
    const TITLE = 'A title';

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
        return CreateSong::create(self::TITLE);
    }

    /**
     * @test
     */
    public function commandHasTitle()
    {
        $command = $this->getCommand();
        $this->assertEquals(
            self::TITLE,
            $command->title()
        );
    }
}
