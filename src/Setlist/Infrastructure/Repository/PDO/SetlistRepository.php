<?php

namespace Setlist\Infrastructure\Repository\PDO;

use Setlist\Domain\Entity\Setlist\ActCollection;
use Setlist\Domain\Entity\Setlist\ActFactory;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsDate;
use Setlist\Domain\Entity\Setlist\Event\SetlistChangedItsName;
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
    private $songRepository;
    private $actFactory;

    const TABLE_NAME = 'setlist';

    public function __construct(
        PDO $PDO,
        SetlistFactory $setlistFactory,
        SongRepository $songRepository,
        ActFactory $actFactory
    ) {
        $this->PDO = $PDO;
        $this->setlistFactory = $setlistFactory;
        $this->songRepository = $songRepository;
        $this->actFactory = $actFactory;
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
            $sql = <<<SQL
SELECT * FROM `setlist_song` WHERE setlist_id = :uuid;
SQL;
            $query = $this->PDO->prepare($sql);
            $query->bindValue('uuid', $uuid);
            $query->execute();
            $setlistSongs = $query->fetchAll(PDO::FETCH_ASSOC);

            $currentAct = 0;
            $acts =
            $actsForSetlist = [];
            foreach ($setlistSongs as $song) {
                if ($song['act'] != $currentAct) {
                    $currentAct = $song['act'];
                }

                $acts[$currentAct][] = $this->songRepository->get(Uuid::create($song['song_id']));
            }

            foreach ($acts as $act) {
                $actsForSetlist[] = $this->actFactory->make($act);
            }

            return $this->setlistFactory->restore(
                $setlistData['id'],
                $actsForSetlist,
                $setlistData['name'],
                $setlistData['date'],
                $setlistData['creation_date'],
                $setlistData['update_date']
            );
        }

        return null;
    }

    private function runQuery($event)
    {
        switch (get_class($event)) {
            case SetlistWasCreated::class:
                $this->insert(
                    $event->id(),
                    $event->name(),
                    $event->actCollection(),
                    $event->formattedDate(),
                    $event->formattedCreationDate()
                );
                break;
            case SetlistChangedItsName::class:
                $this->update($event->id(), 'name', $event->name(), $event->formattedUpdateDate());
                break;
            case SetlistChangedItsDate::class:
                $this->update($event->id(), 'date', $event->date(), $event->formattedUpdateDate());
                break;
//            case SetlistWasDeleted::class:
//                $this->delete($event->id());
//                break;
        }
    }

    private function insert(
        string $uuid,
        string $name,
        ActCollection $actCollection,
        string $formattedDate,
        string $formattedCreationDate
    ) {
        $sql = <<<SQL
INSERT INTO `%s` (id, name, date, creation_date, update_date) VALUES (:uuid, :name, :date, :creation_date, :update_date);
SQL;
        $sql = sprintf($sql, self::TABLE_NAME);
        $query = $this->PDO->prepare($sql);
        $query->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $query->bindValue(':name', $name, PDO::PARAM_STR);
        $query->bindValue(':date', $formattedDate, PDO::PARAM_STR);
        $query->bindValue(':creation_date', $formattedCreationDate, PDO::PARAM_STR);
        $query->bindValue(':update_date', $formattedCreationDate, PDO::PARAM_STR);
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

    private function update(string $uuid, string $parameter, string $value, string $updateDate)
    {
        $sql = <<<SQL
UPDATE `%s` SET %s = :value, update_date = :update_date WHERE id = :uuid;
SQL;
        $sql = sprintf($sql, self::TABLE_NAME, $parameter);
        $query = $this->PDO->prepare($sql);
        $query->bindValue('value', $value);
        $query->bindValue('uuid', $uuid);
        $query->bindValue('update_date', $updateDate);
        $query->execute();
    }
}
