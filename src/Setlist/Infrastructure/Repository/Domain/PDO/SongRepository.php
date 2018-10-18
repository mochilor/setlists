<?php

namespace Setlist\Infrastructure\Repository\Domain\PDO;

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
            return $this->songFactory->restore(
                $songData['id'],
                $songData['title'],
                $songData['creation_date'],
                $songData['update_date']
            );
        }

        return null;
    }

    private function runQuery($event)
    {
        switch (get_class($event)) {
            case SongWasCreated::class:
                $this->insert($event->id(), $event->title(), $event->formattedCreationDate(), $event->formattedUpdateDate());
                break;
            case SongChangedItsTitle::class:
                $this->update($event->id(), $event->title(), $event->formattedUpdateDate());
                break;
            case SongWasDeleted::class:
                $this->delete($event->id());
                break;
        }
    }

    private function insert(string $uuid, string $title, string $formattedCreationDate, string $formattedUpdateDate)
    {
        $sql = <<<SQL
INSERT INTO `%s` (id, title, creation_date, update_date) VALUES (:uuid, :title, :creation_date, :update_date);
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->bindValue(':creation_date', $formattedCreationDate, PDO::PARAM_STR);
        $query->bindValue(':update_date', $formattedUpdateDate, PDO::PARAM_STR);
        $query->execute();
    }

    private function update(string $uuid, string $title, string $formattedUpdateDate)
    {
        $sql = <<<SQL
UPDATE `%s` SET title = :title, update_date = :update_date WHERE id = :uuid;
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->bindValue('title', $title);
        $query->bindValue('update_date', $formattedUpdateDate);
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
