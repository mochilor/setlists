<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent;

use Setlist\Application\Persistence\Setlist\SetlistProjectorRepository as SetlistProjectorRepositoryInterface;
use Setlist\Application\Persistence\Song\PersistedSongCollectionFactory;
use Setlist\Domain\Entity\Setlist\ActCollection;
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
use Setlist\Infrastructure\Repository\Application\Eloquent\Model\SetlistProjection;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Setlist as EloquentSetlist;

class SetlistProjectorRepository implements SetlistProjectorRepositoryInterface
{
    private $persistedSongCollectionFactory;

    public function __construct(PersistedSongCollectionFactory $persistedSongCollectionFactory)
    {
        $this->persistedSongCollectionFactory = $persistedSongCollectionFactory;
    }

    public function save(SetlistWasCreated $event)
    {
        $data = $this->prepareData($event);

        $setlistProjection = new SetlistProjection();
        $setlistProjection->id = $event->id()->value()->value();
        $setlistProjection->data = $data;
        $setlistProjection->save();
    }

    private function prepareData(SetlistWasCreated $event): string
    {
        $data = [
            'id' => $event->id()->value()->value(),
            'name' => $event->name(),
            'description' => $event->description(),
            'date' => $event->formattedDate(),
            'creation_date' => $event->formattedCreationDate(),
            'update_date' => $event->formattedUpdateDate(),
        ];

        $actsArray = $this->getActsArray($event->actCollection());

        $data['acts'] = $actsArray;

        return json_encode($data);
    }

    public function changeName(SetlistChangedItsName $event)
    {
        $setlistProjection = SetlistProjection::find($event->id()->value());

        if (!$setlistProjection) {
            // Throw Exception?
            return;
        }

        $data = json_decode($setlistProjection->data, true);
        $data['name'] = $event->name();
        $setlistProjection->data = json_encode($data);
        $setlistProjection->save();
    }

    public function changeDescription(SetlistChangedItsDescription $event)
    {
        $setlistProjection = SetlistProjection::find($event->id()->value());

        if (!$setlistProjection) {
            // Throw Exception?
            return;
        }

        $data = json_decode($setlistProjection->data, true);
        $data['description'] = $event->description();
        $setlistProjection->data = json_encode($data);
        $setlistProjection->save();
    }

    public function changeDate(SetlistChangedItsDate $event)
    {
        $setlistProjection = SetlistProjection::find($event->id()->value());

        if (!$setlistProjection) {
            // Throw Exception?
            return;
        }

        $data = json_decode($setlistProjection->data, true);
        $data['date'] = $event->formattedDate();
        $setlistProjection->data = json_encode($data);
        $setlistProjection->save();
    }

    public function changeActCollection(SetlistChangedItsActCollection $event)
    {
        $setlistProjection = SetlistProjection::find($event->id()->value());

        if (!$setlistProjection) {
            // Throw Exception?
            return;
        }

        $data = json_decode($setlistProjection->data, true);
        $actsArray = $this->getActsArray($event->actCollection());
        $data['acts'] = $actsArray;
        $setlistProjection->data = json_encode($data);
        $setlistProjection->save();
    }

    public function delete(SetlistWasDeleted $event)
    {
        SetlistProjection::destroy($event->id()->value());
    }

    private function getActsArray(ActCollection $actCollection): array
    {
        $actsArray = [];

        foreach ($actCollection as $act) {
            $actSongs = [];
            foreach ($act->songCollection() as $song) {
                $actSongs[] = [
                    'id' => $song->id()->value(),
                    'title' => $song->title(),
                    'is_visible' => $song->isVisible(),
                    'creation_date' => $song->formattedCreationDate(),
                    'update_date' => $song->formattedUpdateDate(),
                ];
            }

            $actsArray[] = $actSongs;
        }

        return $actsArray;
    }

    public function hideSongInSetlists(SongWasHidden $event)
    {
        $this->updateSongsInSetlist($event->id()->value(), false, 'is_visible', $event->formattedUpdateDate(), 0);
    }

    public function unhideSongInSetlists(SongWasUnhidden $event)
    {
        $this->updateSongsInSetlist($event->id()->value(), false, 'is_visible', $event->formattedUpdateDate(), 1);
    }

    public function changeSongTitleInSetlists(SongChangedItsTitle $event)
    {
        $this->updateSongsInSetlist($event->id()->value(), false, 'title', $event->formattedUpdateDate(), $event->title());
    }

    public function deleteSongInSetlists(SongWasDeleted $event)
    {
        $this->updateSongsInSetlist($event->id()->value(), true);
    }

    private function updateSongsInSetlist(
        string $id,
        bool $delete,
        string $field = null,
        string $updateDate = null,
        $value = null
    ) {
        $setlists = EloquentSetlist::select('id')
            ->whereHas('songs', function ($query) use ($id){
                $query->where('id', $id);
            })
            ->get();

        if ($setlists->isEmpty()) {
            return;
        }

        $setlistsIds = $setlists->pluck('id')->all();
        $setlistProjections = SetlistProjection::whereIn('id', $setlistsIds)->get();

        foreach ($setlistProjections as $setlistProjection) {
            $data = json_decode($setlistProjection->data, true);
            foreach ($data['acts'] as $keyAct => $act) {
                foreach ($act as $keySong => $song) {
                    if ($song['id'] == $id) {
                        if ($delete) {
                            unset($data['acts'][$keyAct][$keySong]);
                        } else {
                            $data['acts'][$keyAct][$keySong][$field] = $value;
                            $data['acts'][$keyAct][$keySong]['update_date'] = $updateDate;
                        }
                    }
                }
            }

            $setlistProjection->data = json_encode($data);
            $setlistProjection->save();
        }
    }
}
