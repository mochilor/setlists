<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent;

use Setlist\Application\Persistence\Song\SongRepository as ApplicationSongRepositoryInterface;
use Setlist\Domain\Entity\Setlist\SongCollection;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Song as EloquentSong;

class SongRepository implements ApplicationSongRepositoryInterface
{
    private $songFactory;

    public function __construct(SongFactory $songFactory)
    {
        $this->songFactory = $songFactory;
    }

    public function getAllTitles(): array
    {
        return EloquentSong::pluck('title')->all();
    }

    public function getOtherTitles(string $uuid): array
    {
        return EloquentSong::where('id', '<>', $uuid)
            ->pluck('title')
            ->all();
    }

    public function getAllSongs(int $start, int $length): SongCollection
    {
        $eloquentSongs = EloquentSong::offset($start)
            ->orderBy('creation_date', 'asc')
            ->limit($length)
            ->get();

        return $this->getSongCollection($eloquentSongs);
    }

    public function getSongsByTitle(string $title): SongCollection
    {
        $eloquentSongs = EloquentSong::where('title', 'like', "%$title%")
            ->orderBy('creation_date', 'asc')
            ->get();

        return $this->getSongCollection($eloquentSongs);
    }

    private function getSongCollection($eloquentSongs): SongCollection
    {
        $songsArray = [];
        foreach ($eloquentSongs as $eloquentSong) {
            $songsArray[] = $this->songFactory->restore(
                $eloquentSong->id,
                $eloquentSong->title,
                $eloquentSong->creation_date,
                $eloquentSong->update_date
            );
        }

        return SongCollection::create(...$songsArray);
    }
}
