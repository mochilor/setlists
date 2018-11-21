<?php

namespace Setlist\Application\Persistence\Setlist;

use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsName;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasDeleted;

interface SetlistProjectorRepository
{
    public function save(SetlistWasCreated $event);
    public function changeName(SetlistChangedItsName $event);
    public function changeDate(SetlistChangedItsDate $event);
    public function changeActCollection(SetlistChangedItsActCollection $event);
    public function delete(SetlistWasDeleted $event);
}
