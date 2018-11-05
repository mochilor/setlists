<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent;

use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepositoryInterface;
use Setlist\Domain\Entity\Setlist\ActFactory;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongFactory;
use \Setlist\Domain\Entity\Song\SongRepository as DomainSongRepositoryInterface;
use Setlist\Domain\Entity\Setlist\SetlistCollection;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Setlist as EloquentSetlist;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Song as EloquentSong;

class SetlistRepository implements ApplicationSetlistRepositoryInterface
{
    private $setlistFactory;
    private $songFactory;
    private $songRepository;
    private $actFactory;

    public function __construct(
        SetlistFactory $setlistFactory,
        SongFactory $songFactory,
        DomainSongRepositoryInterface $songRepository,
        ActFactory $actFactory
    ) {
        $this->setlistFactory = $setlistFactory;
        $this->songFactory = $songFactory;
        $this->songRepository = $songRepository;
        $this->actFactory = $actFactory;
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

    public function getAllSetlists(int $start, int $length): SetlistCollection
    {
        $eloquentSetlists = EloquentSetlist::offset($start)
            ->orderBy('creation_date', 'asc')
            ->limit($length)
            ->get();

        $setlistsForCollection = [];
        foreach ($eloquentSetlists as $eloquentSetlist) {
            $setlistsForCollection[] = $this->getSetlistFromData($eloquentSetlist);
        }

        return SetlistCollection::create(...$setlistsForCollection);
    }

    private function getSetlistFromData($eloquentSetlist)
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
            $eloquentSong->creation_date,
            $eloquentSong->update_date
        );
    }
}
