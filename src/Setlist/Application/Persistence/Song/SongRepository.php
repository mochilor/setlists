<?php

namespace Setlist\Application\Persistence\Song;

interface SongRepository
{
    public function getAllTitles(): array;
    public function getOtherTitles(string $uuid): array;
    public function getOneSongById(string $id): ?PersistedSong;
    public function getAllSongs(int $start, int $length): PersistedSongCollection;
    public function getSongsByTitle(string $title): PersistedSongCollection;
}
