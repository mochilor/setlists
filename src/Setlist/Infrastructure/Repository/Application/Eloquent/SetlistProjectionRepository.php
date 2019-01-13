<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistCollection;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository as ApplicationSetlistRepositoryInterface;
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

        return $this->getSetlistFromData($data, true);
    }

    public function getAllSetlists(int $start, int $length, string $name): PersistedSetlistCollection
    {
        $setlists = SetlistProjection::orderBy('created_at', 'asc')
            ->when(!empty($name), function ($query) use ($name) {
                $whereString = sprintf('data REGEXP \'"name":"[^"]*%s\'', $name);
                return $query->whereRaw($whereString);
            })
            ->get();

        $setlistsArray = [];

        foreach ($setlists as $setlist) {
            $setlistsArray[] = $this->getSetlistFromData(json_decode($setlist->data), true);
        }

        usort($setlistsArray, function($a, $b) {
            return strcasecmp($a->name(), $b->name());
        });

        if ($length > 0) {
            $setlistsArray = array_slice($setlistsArray, $start, $length);
        }

        return PersistedSetlistCollection::create(...$setlistsArray);
    }

    private function getSetlistFromData(\stdClass $data, bool $withActs): PersistedSetlist
    {
        $persistedSongCollections = [];
        if ($withActs) {
            $acts = [];
            foreach ($data->acts as $currentAct => $act) {
                foreach ($act as $song) {
                    $acts[$currentAct][] = $this->getPersistedSong($song);
                }
            }

            foreach ($acts as $act) {
                $persistedSongCollections[] = $this->persistedSongCollectionFactory->make($act);
            }
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

    public function getSetlistsInfoBySongId(string $id): PersistedSetlistCollection
    {
        $setlists = SetlistProjection::orderBy('created_at', 'asc')
            ->where('data', 'like', '%' . $id . '%')
            ->get();

        $setlistsArray = [];

        foreach ($setlists as $setlist) {
            $setlistsArray[] = $this->getSetlistFromData(json_decode($setlist->data), false);
        }

        usort($setlistsArray, function($a, $b) {
            return strcasecmp($a->name(), $b->name());
        });

        return PersistedSetlistCollection::create(...$setlistsArray);
    }
}
