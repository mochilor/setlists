<?php

namespace Setlist\Domain\Entity\Song;

interface SongAvailabilityRepository
{
    public function titleIsAvailable(string $title): bool;
    public function titleIsUnique(string $title, string $uuid): bool;
    public function idIsAvailable(string $id): bool;
}
