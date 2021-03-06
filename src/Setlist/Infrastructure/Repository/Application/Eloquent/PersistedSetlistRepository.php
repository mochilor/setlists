<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistCollection;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository as ApplicationSetlistRepositoryInterface;
use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollectionFactory;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Setlist as EloquentSetlist;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Setlist;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Song as EloquentSong;

class PersistedSetlistRepository implements ApplicationSetlistRepositoryInterface
{
    private $persistedSongCollectionFactory;

    public function __construct(PersistedSongCollectionFactory $persistedSongCollectionFactory)
    {
        $this->persistedSongCollectionFactory = $persistedSongCollectionFactory;
    }

    public function getOneSetlistById(string $id): ?PersistedSetlist
    {
        $eloquentSetlist = EloquentSetlist::find($id);

        if ($eloquentSetlist instanceof EloquentSetlist) {
            return $this->getSetlistFromData($eloquentSetlist, true);
        }

        return null;
    }

    public function getAllSetlists(int $start, int $length, string $name): PersistedSetlistCollection
    {
        $eloquentSetlists = EloquentSetlist::orderBy('name', 'asc')
            ->orderBy('creation_date', 'asc')
            ->when($start > 0, function ($query) use ($start) {
                return $query->skip($start);
            })
            ->when($length > 0, function ($query) use($length) {
                return $query->take($length);
            })
            ->when(!empty($name), function ($query) use($name) {
                return $query->where('name', 'like', '%' .$name . '%');
            })
            ->get();

        $setlistsForCollection = [];
        foreach ($eloquentSetlists as $eloquentSetlist) {
            $setlistsForCollection[] = $this->getSetlistFromData($eloquentSetlist, true);
        }

        return PersistedSetlistCollection::create(...$setlistsForCollection);
    }

    private function getSetlistFromData(Setlist $eloquentSetlist, bool $withSongs): PersistedSetlist
    {
        $persistedSongCollections = [];
        if ($withSongs) {
            $currentAct = 0;
            $acts = [];
            foreach ($eloquentSetlist->songs as $eloquentSong) {
                if ($eloquentSong->pivot->act != $currentAct) {
                    $currentAct = $eloquentSong->pivot->act;
                }

                $acts[$currentAct][$eloquentSong->pivot->order] = $this->getPersistedSong($eloquentSong);
            }

            ksort($acts);
            foreach ($acts as $act) {
                $persistedSongCollections[] = $this->persistedSongCollectionFactory->make($act);
            }
        }

        return new PersistedSetlist(
            $eloquentSetlist->id,
            $persistedSongCollections,
            $eloquentSetlist->name,
            $eloquentSetlist->description,
            $eloquentSetlist->date,
            $eloquentSetlist->creation_date,
            $eloquentSetlist->update_date
        );
    }

    private function getPersistedSong(EloquentSong $eloquentSong): PersistedSong
    {
        return new PersistedSong(
            $eloquentSong->id,
            $eloquentSong->title,
            $eloquentSong->is_visible,
            $eloquentSong->creation_date,
            $eloquentSong->update_date
        );
    }

    public function getSetlistsInfoBySongId(string $id): PersistedSetlistCollection
    {
        $eloquentSetlists = EloquentSetlist::orderBy('name', 'asc')
            ->orderBy('creation_date', 'asc')
            ->join('setlist_song', 'setlist.id', '=', 'setlist_song.setlist_id')
            ->where('setlist_song.song_id', $id)
            ->get();

        $setlistsForCollection = [];
        foreach ($eloquentSetlists as $eloquentSetlist) {
            $setlistsForCollection[] = $this->getSetlistFromData($eloquentSetlist, false);
        }

        return PersistedSetlistCollection::create(...$setlistsForCollection);
    }
}
