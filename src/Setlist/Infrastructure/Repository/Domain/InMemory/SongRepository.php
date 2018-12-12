<?php

namespace Setlist\Infrastructure\Repository\Domain\InMemory;

use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository as SongRepositoryInterface;
use Setlist\Domain\Value\Uuid;

class SongRepository implements SongRepositoryInterface
{
    private $songFactory;
    private $songs = [];

    public function __construct(SongFactory $songFactory)
    {
        $this->songFactory = $songFactory;
    }

    public function save(Song $song)
    {
        $events = $song->events();

        foreach ($events as $event) {
            $this->runQuery($event);
        }
    }

    public function get(Uuid $uuid): ?Song
    {
        foreach ($this->songs as $song) {
            if ($song['uuid'] == $uuid) {
                return $song;
            }
        }

        return null;
    }

    private function runQuery($event)
    {
        switch (get_class($event)) {
            case SongWasCreated::class:
                $this->insert($event->id(), $event->title());
                break;
            case SongChangedItsTitle::class:
                $this->update($event->id(), $event->title());
                break;
            case SongWasDeleted::class:
                $this->delete($event->id());
                break;
        }
    }

    private function insert(string $uuid, string $title)
    {
        $song = [
            'uuid' => $uuid,
            'title' => $title,
        ];

        $this->songs[] = $song;
    }

    private function update(string $uuid, string $title)
    {
        foreach ($this->songs as $key => $song) {
            if ($song['uuid'] == $uuid) {
                $this->songs[$key] = [
                    'uuid' => $uuid,
                    'title' => $title,
                ];
            }
        }

    }

    private function delete(string $uuid)
    {
        foreach ($this->songs as $key => $song) {
            if ($song['uuid'] == $uuid) {
                unset($this->songs[$key]);
            }
        }
    }
}
