<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist;

use DateTime;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SongCollection;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Value\Uuid;

class SetlistTest extends TestCase
{
    const SETLIST_NAME = 'Setlist name';
    const BAD_SETLIST_NAME = 'KK';
    const DATE_FORMAT = 'Y-m-d H:i:s';
    const FULL_DATETIME = '2017-08-31 00:00:00';
    const FORMATTED_DATE = '2017-08-31';

    protected function getSong()
    {
        return $this->getMockBuilder(Song::class)->getMock();
    }

    /**
     * @test
     */
    public function setlistCanBeCreated()
    {
        $setList = $this->getSetlist([$this->getSong()], self::SETLIST_NAME);

        $this->assertInstanceOf(
            Setlist::class,
            $setList
        );
    }

    private function getSetlist(array $songs, string $name): Setlist
    {
        $id = $this->getMockBuilder(Uuid::class)->getMock();
        $songCollection = SongCollection::create(...$songs);
        $date = DateTime::createFromFormat(self::DATE_FORMAT, self::FULL_DATETIME);
        return Setlist::create($id, $songCollection, $name, $date);
    }

    /**
     * @test
     * @expectedException \Setlist\Domain\Exception\Song\InvalidSetlistNameException
     */
    public function setlistWithWrongNameThrowsException()
    {
        $this->getSetlist([$this->getSong()], self::BAD_SETLIST_NAME);
    }

    /**
     * @test
     */
    public function setlistHasName()
    {
        $setList = $this->getSetlist([$this->getSong()], self::SETLIST_NAME);

        $this->assertEquals(
            self::SETLIST_NAME,
            $setList->name()
        );
    }

    /**
     * @test
     */
    public function setlistHasFullName()
    {
        $setList = $this->getSetlist([$this->getSong()], self::SETLIST_NAME);

        $this->assertEquals(
            sprintf('%s - %s', self::FORMATTED_DATE, self::SETLIST_NAME),
            $setList->fullName()
        );
    }

    /**
     * @test
     */
    public function setlistHasSongCollection()
    {
        $setList = $this->getSetlist([$this->getSong()], self::SETLIST_NAME);

        $this->assertInstanceOf(
            SongCollection::class,
            $setList->songCollection()
        );
    }

    /**
     * @test
     */
    public function setlistHasDate()
    {
        $setList = $this->getSetlist([$this->getSong()], self::SETLIST_NAME);

        $this->assertInstanceOf(
            DateTime::class,
            $setList->date()
        );
    }

    /**
     * @test
     */
    public function setlistHasFormattedDate()
    {
        $setList = $this->getSetlist([$this->getSong()], self::SETLIST_NAME);

        $this->assertEquals(
            self::FORMATTED_DATE,
            $setList->formattedDate()
        );
    }

    /**
     * @test
     */
    public function setlistCanChangeItsName()
    {
        $setList = $this->getSetlist([$this->getSong()], self::SETLIST_NAME);

        $newName = "New name";
        $setList->changeName($newName);

        $this->assertEquals(
            $newName,
            $setList->name()
        );
    }

    /**
     * @test
     */
    public function setlistCanChangeItsDate()
    {
        $setList = $this->getSetlist([$this->getSong()], self::SETLIST_NAME);

        $newDate = DateTime::createFromFormat(self::DATE_FORMAT, '2017-08-30 00:00:00');
        $setList->changeDate($newDate);

        $this->assertEquals(
            $newDate,
            $setList->date()
        );
    }

    /**
     * @test
     */
    public function setlistCanChangeItsSongCollection()
    {
        $oldSongs = [
            $this->getSong(),
            $this->getSong(),
        ];
        $setList = $this->getSetlist($oldSongs, self::SETLIST_NAME);

        $newSongs = [
            $this->getSong(),
            $this->getSong(),
            $this->getSong(),
        ];

        foreach ($newSongs as $key => $song) {
            $song->expects($this->any())
                ->method("isEqual")
                ->willReturn(true);
        }

        $songCollection = SongCollection::create(...$newSongs);
        $setList->changeSongCollection($songCollection);

        $this->assertEquals(
            $songCollection,
            $setList->songCollection()
        );
    }
}
