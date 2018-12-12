<?php

namespace Setlist\Infrastructure\Repository\Domain\Eloquent;

use Setlist\Domain\Entity\DomainEvent;
use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Entity\Setlist\ActFactory;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDescription;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsName;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasDeleted;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Setlist\SetlistRepository as SetlistRepositoryInterface;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Value\Uuid;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Setlist as EloquentSetlist;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Song as EloquentSong;

class SetlistRepository implements SetlistRepositoryInterface
{
    const TABLE_NAME = 'setlist';

    private $songFactory;
    private $actFactory;
    private $setlistFactory;
    private $setlistForUpdate;
    private $actCollectionForSetlist;

    public function __construct(SongFactory $songFactory, ActFactory $actFactory, SetlistFactory $setlistFactory)
    {
        $this->songFactory = $songFactory;
        $this->actFactory = $actFactory;
        $this->setlistFactory = $setlistFactory;
    }

    public function save(Setlist $setlist)
    {
        app('db')->transaction(function () use($setlist) {
            $events = $setlist->events();

            foreach ($events as $event) {
                $this->runQuery($event);
            }

            $this->executeUpdate();
        });
    }

    public function get(Uuid $uuid): ?Setlist
    {
        $eloquentSetlist = $songData = EloquentSetlist::with('songs')
            ->find($uuid);

        if ($eloquentSetlist) {
            return $this->getSetlistFromData($eloquentSetlist);
        }

        return null;
    }

    private function getSetlistFromData(EloquentSetlist $eloquentSetlist)
    {
        $currentAct = 0;
        $acts =
        $actsForSetlist = [];
        foreach ($eloquentSetlist->songs as $eloquentSong) {
            if ($eloquentSong->pivot->act != $currentAct) {
                $currentAct = $eloquentSong->pivot->act;
            }

            $acts[$currentAct][$eloquentSong->pivot->order] = $this->makeSong($eloquentSong);
        }

        foreach ($acts as $act) {
            $actsForSetlist[] = $this->actFactory->make($act);
        }

        return $this->setlistFactory->restore(
            $eloquentSetlist->id,
            $actsForSetlist,
            $eloquentSetlist->name,
            $eloquentSetlist->description,
            $eloquentSetlist->date,
            $eloquentSetlist->creation_date,
            $eloquentSetlist->update_date
        );
    }

    private function makeSong(EloquentSong $eloquentSong): Song
    {
        return $this->songFactory->restore(
            $eloquentSong->id,
            $eloquentSong->title,
            $eloquentSong->is_visible,
            $eloquentSong->creation_date,
            $eloquentSong->update_date
        );
    }

    private function runQuery(DomainEvent $event)
    {
        switch (get_class($event)) {
            case SetlistWasCreated::class:
                $this->insert(
                    $event->id(),
                    $event->name(),
                    $event->description(),
                    $event->actCollection(),
                    $event->formattedDate(),
                    $event->formattedCreationDate()
                );
                break;
            case SetlistChangedItsName::class:
                $this->update($event->id(), $event->formattedUpdateDate(), $event->name());
                break;
            case SetlistChangedItsDescription::class:
                $this->update($event->id(), $event->formattedUpdateDate(), null, $event->description());
                break;
            case SetlistChangedItsDate::class:
                $this->update($event->id(), $event->formattedUpdateDate(), null, null, $event->formattedDate());
                break;
            case SetlistChangedItsActCollection::class:
                $this->update($event->id(), $event->formattedUpdateDate(), null, null, null, $event->actCollection());
                break;
            case SetlistWasDeleted::class:
                $this->delete($event->id());
                break;
        }
    }

    private function insert(
        string $uuid,
        string $name,
        string $description,
        ActCollection $actCollection,
        string $formattedDate,
        string $formattedCreationDate
    ) {
        $setlist = EloquentSetlist::create([
            'id' => $uuid,
            'name' => $name,
            'description' => $description,
            'date' => $formattedDate,
            'creation_date' => $formattedCreationDate,
        ]);

        $setlistSongs = $this->getSetlistSongs($actCollection);

        $setlist->songs()->sync($setlistSongs);
    }

    private function update(
        string $uuid,
        string $formattedUpdateDate,
        string $name = null,
        string $description = null,
        string $formattedDate = null,
        ActCollection $actCollection = null
    ) {
        if (empty($this->setlistForUpdate)) {
            $this->setlistForUpdate = EloquentSetlist::find($uuid);
        }

        if ($this->setlistForUpdate instanceof EloquentSetlist) {
            $this->setlistForUpdate->update_date = $formattedUpdateDate;

            if ($name !== null) {
                $this->setlistForUpdate->name = $name;
            }

            if ($description !== null) {
                $this->setlistForUpdate->description = $description;
            }

            if ($formattedDate !== null) {
                $this->setlistForUpdate->date = $formattedDate;
            }

            if ($actCollection !== null) {
                $this->actCollectionForSetlist = $this->getSetlistSongs($actCollection);
            }
        }
    }

    private function getSetlistSongs(ActCollection $actCollection): array
    {
        $setlistSongs = [];
        foreach ($actCollection as $keyAct => $act) {
            foreach ($act->songCollection() as $keySong => $song) {
                $setlistSongs[$song->id()->value()] = [
                    'act' => $keyAct,
                    'order' => $keySong,
                ];

            }
        }

        return $setlistSongs;
    }

    private function executeUpdate()
    {
        if ($this->setlistForUpdate instanceof EloquentSetlist) {
            $this->setlistForUpdate->save();

            if (is_array($this->actCollectionForSetlist)) {
                $this->setlistForUpdate->songs()->sync($this->actCollectionForSetlist);
            }

            $this->setlistForUpdate = null;
            $this->actCollectionForSetlist = null;
        }
    }

    private function delete(string $uuid)
    {
        EloquentSetlist::destroy($uuid);
    }
}
