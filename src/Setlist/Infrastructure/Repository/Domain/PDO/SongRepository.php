<?php

namespace Setlist\Infrastructure\Repository\Domain\PDO;

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
use PDO;
use Setlist\Infrastructure\Exception\PersistenceException;

class SongRepository implements SongRepositoryInterface
{
    private $PDO;
    private $songFactory;

    const TABLE_NAME = 'song';

    public function __construct(PDO $PDO, SongFactory $songFactory)
    {
        $this->PDO = $PDO;
        $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->songFactory = $songFactory;
    }

    public function save(Song $song)
    {
        $this->PDO->beginTransaction();
        try {
            $events = $song->events();
            foreach ($events as $event) {
                $this->runQuery($event);
            }
        } catch (\PDOException $e) {
            $this->PDO->rollBack();
            throw new PersistenceException('Operation could not be performed on Song.');
        }

        $this->PDO->commit();
    }

    public function get(Uuid $uuid): ?Song
    {
        $sql = <<<SQL
SELECT * FROM `%s` WHERE id = :uuid;
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $uuid->value());
        $query->execute();
        $songData = $query->fetch(PDO::FETCH_ASSOC);

        if ($songData) {
            return $this->songFactory->restore(
                $songData['id'],
                $songData['title'],
                $songData['is_visible'],
                $songData['creation_date'],
                $songData['update_date']
            );
        }

        return null;
    }

    private function runQuery(DomainEvent $event)
    {
        switch (get_class($event)) {
            case SongWasCreated::class:
                $this->insert($event->id(), $event->title(), $event->isVisible(), $event->formattedCreationDate(), $event->formattedUpdateDate());
                break;
            case SongChangedItsTitle::class:
                $this->update($event->id(), $event->title(), $event->formattedUpdateDate());
                break;
            case SongWasHidden::class:
                $this->setVisibility($event->id(), false, $event->formattedUpdateDate());
                break;
            case SongWasUnhidden::class:
                $this->setVisibility($event->id(), true, $event->formattedUpdateDate());
                break;
            case SongWasDeleted::class:
                $this->delete($event->id());
                break;
        }
    }

    private function insert(string $uuid, string $title, bool $is_visible, string $formattedCreationDate, string $formattedUpdateDate)
    {
        $sql = <<<SQL
INSERT INTO `%s` (id, title, is_visible, creation_date, update_date) VALUES (:uuid, :title, :is_visible, :creation_date, :update_date);
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $query->bindValue(':title', $title, PDO::PARAM_STR);
        $query->bindValue(':is_visible', $is_visible, PDO::PARAM_INT);
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

        $setlistSongsSql = "DELETE FROM `setlist_song` WHERE song_id = :uuid;";

        $query = $this->PDO->prepare($setlistSongsSql);
        $query->bindValue('uuid', $uuid);
        $query->execute();
    }

    private function setVisibility(string $uuid, bool $visibility, string $formattedUpdateDate)
    {
        $sql = <<<SQL
UPDATE `%s` SET is_visible = :is_visible, update_date = :update_date WHERE id = :uuid;
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->bindValue('is_visible', (int)$visibility, PDO::PARAM_INT);
        $query->bindValue('update_date', $formattedUpdateDate);
        $query->execute();
    }
}
