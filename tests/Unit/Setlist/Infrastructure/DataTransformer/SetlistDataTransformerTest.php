<?php

namespace Tests\Unit\Setlist\Infrastructure\DataTransformer;

use Setlist\Application\DataTransformer\SongDataTransformer;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Entity\Setlist\ActFactory;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Value\Uuid;
use Setlist\Infrastructure\DataTransformer\SetlistDataTransformer;

class SetlistDataTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function dataTransformerCanBeWritenAndReaded()
    {
        $actFactory = new ActFactory();
        $songs = [
            $this->getSongMock(),
            $this->getSongMock(),
        ];
        $act = $actFactory->make($songs);
        $actCollection = ActCollection::create($act);

        $id = Uuid::random();
        $name = 'Setlist name';
        $formattedDate = '2018-01-01';
        $formattedCreationDate =
        $formattedUpdateDate = '2018-01-01 00:00:00';
        $setlist = $this->getMockBuilder(Setlist::class)->getMock();
        $setlist->expects($this->once())
            ->method('actCollection')
            ->willReturn($actCollection);
        $setlist->expects($this->once())
            ->method('id')
            ->willReturn($id);
        $setlist->expects($this->once())
            ->method('name')
            ->willReturn($name);
        $setlist->expects($this->once())
            ->method('formattedDate')
            ->willReturn($formattedDate);
        $setlist->expects($this->once())
            ->method('formattedCreationDate')
            ->willReturn($formattedCreationDate);
        $setlist->expects($this->once())
            ->method('formattedUpdateDate')
            ->willReturn($formattedUpdateDate);

        $songResult = ['Song data!'];
        $resultActs = [
            [
                $songResult,
                $songResult
            ],
        ];
        $songDataTransformer = $this->getMockBuilder(SongDataTransformer::class)->getMock();

        $songDataTransformer
            ->expects($this->any())
            ->method('write');
        $songDataTransformer
            ->expects($this->any())
            ->method('read')
            ->willReturn($songResult);

        $setlistDataTransfomer = new SetlistDataTransformer($songDataTransformer);
        $setlistDataTransfomer->write($setlist);

        $setlistArray = $setlistDataTransfomer->read();

        $this->assertInternalType(
            'array',
            $setlistArray
        );

        $this->assertEquals(
            $setlistArray['id'],
            $id->uuid()
        );

        $this->assertEquals(
            $setlistArray['name'],
            $name
        );

        $this->assertEquals(
            $setlistArray['date'],
            $formattedDate
        );

        $this->assertEquals(
            $setlistArray['acts'],
            $resultActs
        );

        $this->assertEquals(
            $setlistArray['creation_date'],
            $formattedCreationDate
        );

        $this->assertEquals(
            $setlistArray['update_date'],
            $formattedUpdateDate
        );
    }

    private function getSongMock()
    {
        return $this->getMockBuilder(Song::class)->getMock();
    }
}
