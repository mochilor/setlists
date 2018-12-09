<?php

namespace Setlist\Application\Persistence\Song;

interface PersistedSongRepository
{
    public function getOneSongById(string $id): ?PersistedSong;
    public function getAllSongs(int $start, int $length): PersistedSongCollection;
    public function getSongsByTitle(string $title): PersistedSongCollection;
}
