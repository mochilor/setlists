<?php

namespace Setlist\Infrastructure\Messaging\EventHandler\Song;

use Setlist\Domain\Entity\Song\Event\SongWasCreated;

class SongWasCreatedHandler
{
    public function __invoke(SongWasCreated $songWasCreated)
    {
        // Do something cool, like:
        // echo $songWasCreated->title();
        // ;)
    }
}