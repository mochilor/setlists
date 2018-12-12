<?php

namespace Tests\Unit\Setlist\Infrastructure\Value;

use PHPUnit\Framework\TestCase;
use Setlist\Infrastructure\Value\Uuid;
use Setlist\Infrastructure\Value\UuidGenerator;

class UuidGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function generatorCanMakeUuids()
    {
        $generator = new UuidGenerator();

        $value = '8ffd680a-ff57-41f3-ac5e-bf1d877f6950';

        $uuid = $generator->fromString($value);

        $this->assertInstanceOf(
            Uuid::class,
            $uuid
        );
    }
}
