<?php

namespace Tests\Unit\Setlist\Domain\Service\Song;

use Setlist\Domain\Service\Song\SongTitleValidator;
use PHPUnit\Framework\TestCase;

class SongTitleValidatorTest extends TestCase
{
    const TITLES = [
        'Title 1',
        'Title 2',
        'Title 3',
        'Title 4',
    ];

    /**
     * @test
     */
    public function songTitleValidatorCanBeCreated()
    {
        $songTitleValidator = $this->getSongTitleValidator();

        $this->assertInstanceOf(
            SongTitleValidator::class,
            $songTitleValidator
        );
    }

    private function getSongTitleValidator()
    {
        return SongTitleValidator::create(self::TITLES);
    }

    /**
     * @test
     * @dataProvider songTitleValidatorCanBeUsedToCheckUniquenessOfATitleDataProvider
     */
    public function songTitleValidatorCanBeUsedToCheckUniquenessOfATitle($result, $title, $message)
    {
        $songTitleValidator = $this->getSongTitleValidator();

        $this->assertSame(
            $result,
            $songTitleValidator->songTitleIsUnique($title),
            $message
        );
    }

    public function songTitleValidatorCanBeUsedToCheckUniquenessOfATitleDataProvider(): array
    {
        return [
            [
                true,
                'Another title',
                'Check a new Title',
            ],
            [
                false,
                self::TITLES[0],
                'Check an existent title',
            ],
        ];
    }
}
