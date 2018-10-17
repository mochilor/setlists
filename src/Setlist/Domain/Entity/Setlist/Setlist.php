<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTime;
use DateTimeImmutable;
use Setlist\Domain\Entity\EventsTrigger;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsName;
use Setlist\Domain\Exception\Setlist\InvalidActCollectionException;
use Setlist\Domain\Exception\Setlist\InvalidSetlistNameException;
use Setlist\Domain\Value\Uuid;

class Setlist
{
    private $id;
    protected $actCollection;
    private $name;
    private $date;
    private $eventsTrigger;
    private $creationDate;
    private $updateDate;

    const MIN_NAME_LENGTH = 3;
    const MAX_NAME_LENGTH = 30;
    const DATE_TIME_FORMAT = 'Y-m-d';
    const CREATION_DATE_FORMAT = 'Y-m-d H:i:s';
    const UPDATE_DATE_FORMAT = 'Y-m-d H:i:s';

    public static function create(
        Uuid $id,
        ActCollection $actCollection,
        string $name,
        DateTime $date,
        DateTimeImmutable $creationDate,
        DateTimeImmutable $updateDate,
        EventsTrigger $eventsTrigger
    ): self
    {
        $setlist = new static();
        $setlist->eventsTrigger = $eventsTrigger;

        $setlist->setId($id);
        $setlist->setActCollection($actCollection);
        $setlist->setName($name);
        $setlist->setDate($date);
        $setlist->setCreationDate($creationDate);
        $setlist->setUpdateDate($updateDate);

        return $setlist;
    }

    private function setId(Uuid $id)
    {
        $this->id = $id;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    protected function setActCollection(ActCollection $actCollection)
    {
        if ($actCollection->count() == 0) {
            throw new InvalidActCollectionException('No acts provided for the Setlist');
        }

        $this->actCollection = $actCollection;
    }

    private function setName(string $name)
    {
        $this->guardName($name);
        $this->name = $name;
    }

    private function guardName(string $name)
    {
        if (empty($name) || strlen($name) < self::MIN_NAME_LENGTH || strlen($name) > self::MAX_NAME_LENGTH) {
            throw new InvalidSetlistNameException('Invalid name provided for the Setlist');
        }
    }

    private function setDate(DateTime $date)
    {
        $this->date = $date;
    }

    private function setCreationDate(DateTimeImmutable $dateTime)
    {
        $this->creationDate = $dateTime;
    }

    private function setUpdateDate(DateTimeImmutable $updateDate)
    {
        $this->updateDate = $updateDate;
    }

    public function actCollection(): ActCollection
    {
        return $this->actCollection;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function fullName(): string
    {
        return sprintf('%s - %s', $this->formattedDate(), $this->name());
    }

    public function date(): DateTime
    {
        return $this->date;
    }

    public function formattedDate(): string
    {
        return $this->date->format(self::DATE_TIME_FORMAT);
    }

    public function creationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function formattedCreationDate(): string
    {
        return $this->creationDate->format(self::CREATION_DATE_FORMAT);
    }

    public function updateDate(): DateTimeImmutable
    {
        return $this->updateDate;
    }

    public function formattedUpdateDate(): string
    {
        return $this->updateDate->format(self::UPDATE_DATE_FORMAT);
    }

    public function changeName(string $name)
    {
        if ($name != $this->name()) {
            $this->setName($name);
            $newUpdateDate = new DateTimeImmutable();
            $this->setUpdateDate($newUpdateDate);

            $this->eventsTrigger->trigger(
                SetlistChangedItsName::create(
                    $this->id(),
                    $name,
                    $this->formattedUpdateDate()
                )
            );
        }
    }

    public function changeDate(DateTime $date)
    {
        if ($date->format(self::DATE_TIME_FORMAT) != $this->formattedDate()) {
            $this->setDate($date);
            $newUpdateDate = new DateTimeImmutable();
            $this->setUpdateDate($newUpdateDate);

            $this->eventsTrigger->trigger(
                SetlistChangedItsDate::create(
                    $this->id(),
                    $date->format(self::DATE_TIME_FORMAT),
                    $this->formattedUpdateDate()
                )
            );
        }
    }

    public function changeActCollection(ActCollection $actCollection)
    {
        $canChange = false;

        if ($actCollection->count() != $this->actCollection()->count()) {
            $canChange = true;
        }

        foreach ($actCollection as $key => $act) {
            if (!$act->isEqual($this->actCollection()[$key])) {
                $canChange = true;
                break;
            }
        }

        if ($canChange) {
            $this->setActCollection($actCollection);
            $this->eventsTrigger->trigger(
                SetlistChangedItsActCollection::create(
                    $this->id(),
                    $actCollection,
                    $this->formattedUpdateDate()
                )
            );
        }
    }

    public function events(): array
    {
        return $this->eventsTrigger->events();
    }
}
