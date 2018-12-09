<?php

namespace Tests\Unit\Setlist\Application\Command;

use PHPUnit\Framework\TestCase;
use Setlist\Application\Command\BaseCommand;

class BaseCommandTest extends TestCase
{
    /**
     * @test
     */
    public function commandHasSuccessCode()
    {
        $command = new class([]) extends BaseCommand{};

        $this->assertEquals(
            $command->successCode(),
            BaseCommand::SUCCESS_CODE
        );
    }
}
