<?php

namespace Setlist\Domain\Entity\Song;

interface SongTitleRepository
{
    public function titleIsAvailable(string $title): bool;
    public function titleIsUnique(string $title, string $uuid): bool;
}
