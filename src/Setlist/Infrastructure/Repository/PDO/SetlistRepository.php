<?php

namespace Setlist\Infrastructure\Repository\PDO;

use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistRepository as SetlistRepositoryInterface;
use Setlist\Domain\Value\Uuid;
use PDO;

class SetlistRepository implements SetlistRepositoryInterface
{
    private $PDO;
    private $setlistFactory;

    const TABLE_NAME = 'setlist';

    public function __construct(PDO $PDO, SetlistFactory $setlistFactory)
    {
        $this->PDO = $PDO;
        $this->setlistFactory = $setlistFactory;
    }

    public function nextIdentity(): Uuid
    {
        return Uuid::random();
    }

    public function save(Setlist $setlist)
    {
        $events = $setlist->events();

        foreach ($events as $event) {
            $this->runQuery($event);
        }
    }

    public function get(Uuid $uuid): ?Setlist
    {
        $sql = <<<SQL
SELECT * FROM `%s` WHERE id = :uuid;
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->execute();
        $setlistData = $query->fetch(PDO::FETCH_ASSOC);

        if ($setlistData) {
            return $this->setlistFactory->restore(
                $setlistData['id'],
                $setlistData['acts'], // Hay que hacer select de las canciones y agruparlas en acts
                $setlistData['title'],
                $setlistData['date']
            );
        }

        return null;
    }

    private function runQuery($event)
    {
        switch (get_class($event)) {
            case SetlistWasCreated::class:
                $this->insert($event->id(), $event->name(), $event->actCollection(), $event->formattedDate());
                break;
//            case SetlistChangedItsName::class:
//                $this->update($event->id(), $event->title());
//                break;
//            case SetlistWasDeleted::class:
//                $this->delete($event->id());
//                break;
        }
    }

    private function insert(string $uuid, string $name, ActCollection $actCollection, string $formattedDate)
    {
        $sql = <<<SQL
INSERT INTO `%s` (id, name, date) VALUES (:uuid, :name, :date);
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $query->bindValue(':name', $name, PDO::PARAM_STR);
        $query->bindValue(':date', $formattedDate, PDO::PARAM_STR);
        $query->execute();

        $songSql = "INSERT INTO `setlist_song` (setlist_id, song_id, act) VALUES ";
        foreach ($actCollection as $keyAct => $act) {
            foreach ($act->songCollection() as $keySong => $song) {
                $songSql .= sprintf("('%s', '%s', %d),", $uuid, $song->id(), $keyAct);
            }
        }

        if (isset($keySong)) {
            $songSql = substr($songSql, 0, -1) . ";";
            $songQuery = $this->PDO->prepare($songSql);
            $songQuery->execute();
        }
    }
}
