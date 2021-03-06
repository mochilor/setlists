<?php

namespace Tests\Unit\Setlist\Domain\Entity\Setlist;

use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Setlist\Domain\Entity\EventBus;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Setlist\Act;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDescription;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsName;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasDeleted;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Value\Uuid;

class SetlistTest extends TestCase
{
    const SETLIST_NAME = 'Setlist name';
    const BAD_SETLIST_NAME = 'KK';
    const DESCRIPTION = 'Description';
    const DATE_FORMAT = 'Y-m-d H:i:s';
    const FULL_DATETIME = '2017-08-31 00:00:00';
    const FORMATTED_DATE = '2017-08-31';

    protected function getAct($isEqual = false)
    {
        $act = $this->getMockBuilder(Act::class)->getMock();

        $act->expects($this->any())
            ->method('isEqual')
            ->willReturn($isEqual);

        return $act;
    }

    /**
     * @test
     */
    public function setlistCanBeCreated()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

        $this->assertInstanceOf(
            Setlist::class,
            $setList
        );

        $this->assertCount(
            1,
            $setList->events()
        );

        $this->assertInstanceOf(
            SetlistWasCreated::class,
            $setList->events()[0]
        );
    }

    /**
     * @test
     */
    public function setlistCanBeCreatedWithEmptyDescription()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, '');

        $this->assertInstanceOf(
            Setlist::class,
            $setList
        );
    }

    private function getSetlist(array $acts, string $name, string $description): Setlist
    {
        $id = $this->getMockBuilder(Uuid::class)->getMock();
        $actCollection = ActCollection::create(...$acts);
        $date = DateTime::createFromFormat(self::DATE_FORMAT, self::FULL_DATETIME);
        $creationDate = DateTimeImmutable::createFromFormat(self::DATE_FORMAT, self::FULL_DATETIME);
        $updateDate = DateTimeImmutable::createFromFormat(self::DATE_FORMAT, self::FULL_DATETIME);
        $eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $eventsTrigger = new EventsTrigger($eventBus);

        return Setlist::create($id, $actCollection, $name, $description, $date, $creationDate, $updateDate, $eventsTrigger);
    }

    /**
     * @test
     * @expectedException \Setlist\Domain\Exception\Setlist\InvalidSetlistNameException
     */
    public function setlistWithWrongNameThrowsException()
    {
        $this->getSetlist([$this->getAct()], self::BAD_SETLIST_NAME, self::DESCRIPTION);
    }

    /**
     * @test
     * @expectedException \Setlist\Domain\Exception\Setlist\InvalidActCollectionException
     */
    public function setlistWithEmptyActCollectionThrowsException()
    {
        $this->getSetlist([], self::SETLIST_NAME, self::DESCRIPTION);
    }

    /**
     * @test
     */
    public function setlisHasId()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

        $this->assertInstanceOf(
            Uuid::class,
            $setList->id()
        );
    }

    /**
     * @test
     */
    public function setlistHasName()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

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
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

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
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

        $this->assertInstanceOf(
            ActCollection::class,
            $setList->actCollection()
        );
    }

    /**
     * @test
     */
    public function setlistHasDate()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

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
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

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
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

        $newName = "New name";
        $setList->changeName($newName);

        $this->assertEquals(
            $newName,
            $setList->name()
        );

        $this->assertCount(
            2,
            $setList->events()
        );

        $this->assertInstanceOf(
            SetlistChangedItsName::class,
            $setList->events()[1]
        );
    }

    /**
     * @test
     */
    public function setlistCanChangeItsDescription()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

        $newDescription = "New description";
        $setList->changeDescription($newDescription);

        $this->assertEquals(
            $newDescription,
            $setList->description()
        );

        $this->assertCount(
            2,
            $setList->events()
        );

        $this->assertInstanceOf(
            SetlistChangedItsDescription::class,
            $setList->events()[1]
        );
    }

    /**
     * @test
     */
    public function setlistCanChangeItsDate()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

        $newDate = DateTime::createFromFormat(self::DATE_FORMAT, '2017-08-30 00:00:00');
        $setList->changeDate($newDate);

        $this->assertEquals(
            $newDate,
            $setList->date()
        );

        $this->assertCount(
            2,
            $setList->events()
        );

        $this->assertInstanceOf(
            SetlistChangedItsDate::class,
            $setList->events()[1]
        );
    }

    /**
     * @test
     * @dataProvider isEqualDataProvider
     */
    public function setlistCanChangeItsActCollection($actArray1, $actArray2, $result, $message)
    {
        $newActCollection = ActCollection::create(...$actArray2);

        $setList = $this->getSetlist($actArray1, self::SETLIST_NAME, self::DESCRIPTION);

        $setList->changeActCollection($newActCollection);

        $this->assertCount(
            $result,
            $setList->events(),
            $message
        );

        if ($result > 1) {
            $this->assertInstanceOf(
                SetlistChangedItsActCollection::class,
                $setList->events()[1]
            );
        }
    }

    public function isEqualDataProvider()
    {
        return [
            [
                [$this->getAct(true)],
                [$this->getAct(true)],
                1,
                'Same number of acts and equal acts'
            ],
            [
                [$this->getAct(true)],
                [$this->getAct(false)],
                2,
                'Same number of acts and different acts'
            ],
            [
                [$this->getAct(true), $this->getAct(true)],
                [$this->getAct(true)],
                2,
                'Different number of acts and equal acts'
            ],
            [
                [$this->getAct(false), $this->getAct(false)],
                [$this->getAct(false)],
                2,
                'Different number of acts and different acts'
            ],
        ];
    }

    /**
     * @test
     */
    public function setlistHasUpdateDate()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);
        $dateTime = DateTimeImmutable::createFromFormat(Setlist::UPDATE_DATE_FORMAT, self::FULL_DATETIME);

        $this->assertEquals(
            $dateTime,
            $setList->updateDate()
        );
    }

    /**
     * @test
     */
    public function setlistHasFormattedUpdateDate()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

        $this->assertEquals(
            self::FULL_DATETIME,
            $setList->formattedUpdateDate()
        );
    }

    /**
     * @test
     */
    public function setlistHasCreationDate()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);
        $dateTime = DateTimeImmutable::createFromFormat(Setlist::CREATION_DATE_FORMAT, self::FULL_DATETIME);

        $this->assertEquals(
            $dateTime,
            $setList->creationDate()
        );
    }

    /**
     * @test
     */
    public function setlistHasFormattedCreationDate()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

        $this->assertEquals(
            self::FULL_DATETIME,
            $setList->formattedCreationDate()
        );
    }

    /**
     * @test
     */
    public function setlistCanBeDeleted()
    {
        $setList = $this->getSetlist([$this->getAct()], self::SETLIST_NAME, self::DESCRIPTION);

        $setList->delete();

        $this->assertCount(
            2,
            $setList->events()
        );

        $this->assertInstanceOf(
            SetlistWasDeleted::class,
            $setList->events()[1]
        );
    }
}
