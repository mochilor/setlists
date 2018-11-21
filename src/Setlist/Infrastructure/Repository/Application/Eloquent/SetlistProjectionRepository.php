<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistCollection;
use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepositoryInterface;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollectionFactory;
use Setlist\Infrastructure\Repository\Application\Eloquent\Model\SetlistProjection;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Setlist as EloquentSetlist;

class SetlistProjectionRepository implements ApplicationSetlistRepositoryInterface
{
    private $persistedSongCollectionFactory;

    public function __construct(PersistedSongCollectionFactory $persistedSongCollectionFactory)
    {
        $this->persistedSongCollectionFactory = $persistedSongCollectionFactory;
    }

    public function getAllNames(): array
    {
        return EloquentSetlist::pluck('name')->all();
    }

    public function getOtherNames(string $uuid): array
    {
        return EloquentSetlist::where('id', '<>', $uuid)
            ->pluck('name')
            ->all();
    }

    public function getOneSetlistById(string $id): ?PersistedSetlist
    {
        $setlist = SetlistProjection::find($id);

        if (!$setlist instanceof SetlistProjection) {
            return null;
        }

        $data = json_decode($setlist->data);

        return $this->getSetlistFromData($data);
    }

    public function getAllSetlists(int $start, int $length): PersistedSetlistCollection
    {
        $setlists = SetlistProjection::orderBy('created_at', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $setlistsArray = [];

        foreach ($setlists as $setlist) {
            $setlistsArray[] = $this->getSetlistFromData(json_decode($setlist->data));
        }

        return PersistedSetlistCollection::create(...$setlistsArray);
    }

    private function getSetlistFromData($setlistProjection): PersistedSetlist
    {
        $acts = [];
        foreach ($setlistProjection->acts as $currentAct => $act) {
            foreach ($act as $song) {
                $acts[$currentAct][] = $this->getPersistedSong($song);
            }
        }

        $persistedSongCollections = [];
        foreach ($acts as $act) {
            $persistedSongCollections[] = $this->persistedSongCollectionFactory->make($act);
        }

        return new PersistedSetlist(
            $setlistProjection->id,
            $persistedSongCollections,
            $setlistProjection->name,
            $setlistProjection->date,
            $setlistProjection->creation_date,
            $setlistProjection->update_date
        );
    }

    private function getPersistedSong($song): PersistedSong
    {
        return new PersistedSong(
            $song->id,
            $song->title,
            $song->creation_date,
            $song->update_date
        );
    }
}
