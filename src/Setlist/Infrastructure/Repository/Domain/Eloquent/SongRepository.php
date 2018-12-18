<?php

namespace Setlist\Infrastructure\Repository\Domain\Eloquent;

use Setlist\Domain\Entity\DomainEvent;
use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Entity\Song\Event\SongWasHidden;
use Setlist\Domain\Entity\Song\Event\SongWasUnhidden;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository as SongRepositoryInterface;
use Setlist\Domain\Value\Uuid;
use Setlist\Infrastructure\Repository\Domain\Eloquent\Model\Song as EloquentSong;

class SongRepository implements SongRepositoryInterface
{
    const TABLE_NAME = 'song';

    private $songFactory;

    public function __construct(SongFactory $songFactory)
    {
        $this->songFactory = $songFactory;
    }

    public function save(Song $song)
    {
        app('db')->transaction(function () use($song) {
            $events = $song->events();

            foreach ($events as $event) {
                $this->runQuery($event);
            }
        });
    }

    private function runQuery(DomainEvent $event)
    {
        switch (get_class($event)) {
            case SongWasCreated::class:
                $this->insert($event->id()->value(), $event->title(), true, $event->formattedCreationDate(), $event->formattedUpdateDate());
                break;
            case SongChangedItsTitle::class:
                $this->update($event->id()->value(), $event->title(), $event->formattedUpdateDate());
                break;
            case SongWasHidden::class:
                $this->setVisibility($event->id()->value(), false, $event->formattedUpdateDate());
                break;
            case SongWasUnhidden::class:
                $this->setVisibility($event->id()->value(), true, $event->formattedUpdateDate());
                break;
            case SongWasDeleted::class:
                $this->delete($event->id()->value());
                break;
        }
    }

    public function get(Uuid $uuid): ?Song
    {
        $songData = EloquentSong::find($uuid->value());

        if ($songData) {
            return $this->songFactory->restore(
                $songData->id,
                $songData->title,
                $songData->is_visible,
                $songData->creation_date,
                $songData->update_date
            );
        }

        return null;
    }

    private function insert(string $uuid, string $title, bool $isVisible, string $formattedCreationDate, string $formattedUpdateDate)
    {
        EloquentSong::create([
            'id' => $uuid,
            'title' => $title,
            'is_visible' => $isVisible,
            'creation_date' => $formattedCreationDate,
            'update_date' => $formattedUpdateDate,
        ]);
    }

    private function update(string $uuid, string $title, string $formattedUpdateDate)
    {
        EloquentSong::where('id', $uuid)
            ->update([
                'title' => $title,
                'update_date' => $formattedUpdateDate,
            ]);
    }

    private function setVisibility(string $uuid, bool $visibility, string $formattedUpdateDate)
    {
        EloquentSong::where('id', $uuid)
            ->update([
                'is_visible' => $visibility,
                'update_date' => $formattedUpdateDate,
            ]);
    }

    private function delete(string $uuid)
    {
        EloquentSong::destroy($uuid);
    }
}
