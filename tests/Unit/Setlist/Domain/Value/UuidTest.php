<?php

namespace Tests\Setlist\Domain\Value;

use Setlist\Domain\Value\Uuid;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    private function getUuid(): Uuid
    {
        return Uuid::random();
    }

    /**
     * @test
     */
    public function uuidCanBeCompared()
    {
        $uuid = $this->getUuid();
        $otherUuid = $this->getUuid();

        $this->assertNotEquals(
            true,
            $uuid->equals($otherUuid)
        );
    }

    /**
     * @test
     * @expectedException \Setlist\Domain\Exception\InvalidUuidException
     */
    public function badStringThrowsException()
    {
        Uuid::create('Hello!');
    }

    /**
     * @test
     */
    public function uuidCanBeCastedToString()
    {
        $uuid = $this->getUuid();
        $string = $uuid . '!';

        $this->assertTrue(is_string($string));
    }
}
