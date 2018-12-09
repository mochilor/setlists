<?php

namespace Setlist\Application\Persistence\Setlist;

use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDescription;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsName;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasDeleted;
use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Entity\Song\Event\SongWasHidden;
use Setlist\Domain\Entity\Song\Event\SongWasUnhidden;

interface SetlistProjectorRepository
{
    public function save(SetlistWasCreated $event);
    public function changeName(SetlistChangedItsName $event);
    public function changeDescription(SetlistChangedItsDescription $event);
    public function changeDate(SetlistChangedItsDate $event);
    public function changeActCollection(SetlistChangedItsActCollection $event);
    public function delete(SetlistWasDeleted $event);
    public function hideSongInSetlists(SongWasHidden $event);
    public function unhideSongInSetlists(SongWasUnhidden $event);
    public function changeSongTitleInSetlists(SongChangedItsTitle $event);
    public function deleteSongInSetlists(SongWasDeleted $event);
}
