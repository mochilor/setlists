<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistCollection;
use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepositoryInterface;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollectionFactory;
use Setlist\Infrastructure\Repository\Application\Eloquent\Model\SetlistProjection;

class SetlistProjectionRepository implements ApplicationSetlistRepositoryInterface
{
    private $persistedSongCollectionFactory;

    public function __construct(PersistedSongCollectionFactory $persistedSongCollectionFactory)
    {
        $this->persistedSongCollectionFactory = $persistedSongCollectionFactory;
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
            ->when($start > 0, function ($query) use ($start) {
                return $query->skip($start);
            })
            ->when($length > 0, function ($query) use($length) {
                return $query->take($length);
            })
            ->get();

        $setlistsArray = [];

        foreach ($setlists as $setlist) {
            $setlistsArray[] = $this->getSetlistFromData(json_decode($setlist->data));
        }

        return PersistedSetlistCollection::create(...$setlistsArray);
    }

    private function getSetlistFromData($data): PersistedSetlist
    {
        $acts = [];
        foreach ($data->acts as $currentAct => $act) {
            foreach ($act as $song) {
                $acts[$currentAct][] = $this->getPersistedSong($song);
            }
        }

        $persistedSongCollections = [];
        foreach ($acts as $act) {
            $persistedSongCollections[] = $this->persistedSongCollectionFactory->make($act);
        }

        return new PersistedSetlist(
            $data->id,
            $persistedSongCollections,
            $data->name,
            $data->description,
            $data->date,
            $data->creation_date,
            $data->update_date
        );
    }

    private function getPersistedSong($song): PersistedSong
    {
        return new PersistedSong(
            $song->id,
            $song->title,
            $song->is_visible,
            $song->creation_date,
            $song->update_date
        );
    }
}
