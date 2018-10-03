<?php

namespace Setlist\Infrastructure\Repository\PDO;

use Setlist\Domain\Entity\Song\Event\SongChangedItsTitle;
use Setlist\Domain\Entity\Song\Event\SongWasCreated;
use Setlist\Domain\Entity\Song\Event\SongWasDeleted;
use Setlist\Domain\Entity\Song\Song;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Domain\Entity\Song\SongRepository as SongRepositoryInterface;
use Setlist\Domain\Value\Uuid;
use PDO;

class SongRepository implements SongRepositoryInterface
{
    private $PDO;
    private $songFactory;

    const TABLE_NAME = 'song';

    public function __construct(PDO $PDO, SongFactory $songFactory)
    {
        $this->PDO = $PDO;
        $this->songFactory = $songFactory;
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
        $sql = <<<SQL
SELECT * FROM `%s` WHERE id = :uuid;
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->execute();
        $songData = $query->fetch(PDO::FETCH_ASSOC);

        if ($songData) {
            return $this->songFactory->restore($songData['id'], $songData['title'], $songData['created_at']);
        }

        return null;
    }

    private function runQuery($event)
    {
        switch (get_class($event)) {
            case SongWasCreated::class:
                $this->insert($event->id(), $event->title(), $event->formattedDateTime());
                break;
            case SongChangedItsTitle::class:
                $this->update($event->id(), $event->title());
                break;
            case SongWasDeleted::class:
                $this->delete($event->id());
                break;
        }
    }

    private function insert(string $uuid, string $title, string $formattedDateTime)
    {
        $sql = <<<SQL
INSERT INTO `%s` (id, title, created_at) VALUES (:uuid, :title, :datetime);
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->bindValue(':datetime', $formattedDateTime, PDO::PARAM_STR);
        $query->execute();
    }

    private function update(string $uuid, string $title)
    {
        $sql = <<<SQL
UPDATE `%s` SET title = :title WHERE id = :uuid;
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue('title', $title);
        $query->bindValue('uuid', $uuid);
        $query->execute();
    }

    private function delete(string $uuid)
    {
        $sql = <<<SQL
DELETE FROM `%s` WHERE id = :uuid;
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->execute();
    }
}
