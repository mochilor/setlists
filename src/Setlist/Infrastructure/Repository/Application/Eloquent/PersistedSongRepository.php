<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent;

use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollection;
use Setlist\Application\Persistence\Song\PersistedSongRepository as ApplicationSongRepositoryInterface;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Song as EloquentSong;

class PersistedSongRepository implements ApplicationSongRepositoryInterface
{
    public function getOneSongById(string $id): ?PersistedSong
    {
        $eloquentSong = EloquentSong::find($id);

        if ($eloquentSong instanceof EloquentSong) {
            return $this->getPersistedSong($eloquentSong);
        }
    }

    public function getAllSongs(int $start, int $length): PersistedSongCollection
    {
        $eloquentSongs = EloquentSong::orderBy('creation_date', 'asc')
            ->when($start > 0, function ($query) use ($start) {
                return $query->skip($start);
            })
            ->when($length > 0, function ($query) use($length) {
                return $query->take($length);
            })
            ->get();

        return $this->getSongCollection($eloquentSongs);
    }

    public function getSongsByTitle(string $title): PersistedSongCollection
    {
        $eloquentSongs = EloquentSong::where('title', 'like', "%$title%")
            ->orderBy('creation_date', 'asc')
            ->get();

        return $this->getSongCollection($eloquentSongs);
    }

    private function getSongCollection($eloquentSongs): PersistedSongCollection
    {
        $songsArray = [];
        foreach ($eloquentSongs as $eloquentSong) {
            $songsArray[] = $this->getPersistedSong($eloquentSong);
        }

        return PersistedSongCollection::create(...$songsArray);
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
}