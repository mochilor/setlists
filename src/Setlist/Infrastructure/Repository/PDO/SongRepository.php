<?php

namespace Setlist\Infrastructure\Repository\PDO;

use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongRepository as SongRepositoryInterface;
use Setlist\Domain\Value\Uuid;
use PDO;

class SongRepository implements SongRepositoryInterface
{
    private $PDO;

    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function nextIdentity(): Uuid
    {
        return Uuid::random();
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
        // TODO: Implement get() method.
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
        $sql = <<<SQL
INSERT INTO `song` (id, title) VALUES (:uuid, :title);
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->execute();
    }

    private function update(string $uuid, string $title)
    {
        $sql = <<<SQL
UPDATE `songs` SET title = :title WHERE id = :uuid;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('title', $title);
        $query->bindValue('uuid', $uuid);
        $query->execute();
    }

    private function delete(string $uuid)
    {
        $sql = <<<SQL
DELETE FROM `songs` WHERE id = :uuid;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->execute();
    }
}
