<?php

namespace Setlist\Domain\Entity\Setlist;

use DateTime;
use Setlist\Domain\Entity\EventsTrigger;
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

    const MIN_NAME_LENGTH = 3;
    const MAX_NAME_LENGTH = 30;
    const DATE_TIME_FORMAT = 'Y-m-d';

    public static function create(
        Uuid $id,
        ActCollection $actCollection,
        string $name,
        DateTime $date,
        EventsTrigger $eventsTrigger
    ): self
    {
        $setlist = new static();
        $setlist->eventsTrigger = $eventsTrigger;

        $setlist->setId($id);
        $setlist->setActCollection($actCollection);
        $setlist->setName($name);
        $setlist->setDatetime($date);

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
            throw new InvalidActCollectionException;
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
            throw new InvalidSetlistNameException();
        }
    }

    private function setDatetime(DateTime $date)
    {
        $this->date = $date;
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

    public function changeName(string $name)
    {
        if ($name != $this->name()) {
            $this->setName($name);
            // Event
        }
    }

    public function changeDate(DateTime $date)
    {
        if ($date !== $this->date()) {
            $this->setDatetime($date);
            // Event
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
                // Event
                break;
            }
        }

        if ($canChange) {
            $this->setActCollection($actCollection);
        }
    }

    public function events(): array
    {
        return $this->eventsTrigger->events();
    }
}
