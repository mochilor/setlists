<?php

namespace Tests\Unit\Setlist\Domain\Service\Setlist;

use Setlist\Domain\Service\Setlist\SetlistNameValidator;
use PHPUnit\Framework\TestCase;

class SetlistTitleValidatorTest extends TestCase
{
    const NAMES = [
        'Name 1',
        'Name 2',
        'Name 3',
        'Name 4',
    ];

    /**
     * @test
     */
    public function songTitleValidatorCanBeCreated()
    {
        $songTitleValidator = $this->getSetlistNameValidator();

        $this->assertInstanceOf(
            SetlistNameValidator::class,
            $songTitleValidator
        );
    }

    private function getSetlistNameValidator()
    {
        return SetlistNameValidator::create(self::NAMES);
    }

    /**
     * @test
     * @dataProvider setlistNameValidatorCanBeUsedToCheckUniquenessOfANameDataProvider
     */
    public function setlistNameValidatorCanBeUsedToCheckUniquenessOfAName($result, $name, $message)
    {
        $setlistNameValidator = $this->getSetlistNameValidator();

        $this->assertSame(
            $result,
            $setlistNameValidator->setlistNameIsUnique($name),
            $message
        );
    }

    public function setlistNameValidatorCanBeUsedToCheckUniquenessOfANameDataProvider(): array
    {
        return [
            [
                true,
                'Another name',
                'Check a new name',
            ],
            [
                false,
                self::NAMES[0],
                'Check an existent name',
            ],
        ];
    }
}
