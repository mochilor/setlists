<?php

namespace Tests\Unit\Setlist\Infrastructure\Value;

use PHPUnit\Framework\TestCase;
use Setlist\Infrastructure\Value\Uuid;

class UuidTest extends TestCase
{
    private $uuid;

    const UUID_VALUE = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';

    public function setUp()
    {
        $this->uuid = new Uuid(self::UUID_VALUE);
    }

    /**
     * @test
     */
    public function uuidHasValue()
    {
        $this->assertEquals(
            self::UUID_VALUE,
            $this->uuid->value()
        );
    }

    /**
     * @test
     */
    public function uuidsCanBeCompared()
    {
        $otherUuid = new Uuid(self::UUID_VALUE);

        $this->assertTrue(
            $this->uuid->equals($otherUuid)
        );

        $differentUuid = new Uuid('8ffd680a-ff57-41f3-ac5e-bf1d877f6951');

        $this->assertFalse(
            $this->uuid->equals($differentUuid)
        );
    }

    /**
     * @test
     * @expectedException \Setlist\Domain\Exception\InvalidUuidException
     */
    public function invalidValueThrowsException()
    {
        new Uuid('wrong value');
    }
}
