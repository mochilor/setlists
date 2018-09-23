<?php

namespace Tests\Unit\Setlist\Application\Command;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\UpdateSong;
use Setlist\Domain\Value\Uuid;

class UpdateSongTest extends TestCase
{
    const TITLE = 'A title';
    const UUID = '550e8400-e29b-41d4-a716-446655440000';

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
        return UpdateSong::create(self::UUID, self::TITLE);
    }

    /**
     * @test
     */
    public function commandHasUuid()
    {
        $command = $this->getCommand();
        $this->assertEquals(
            self::UUID,
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
            self::TITLE,
            $command->title()
        );
    }
}
