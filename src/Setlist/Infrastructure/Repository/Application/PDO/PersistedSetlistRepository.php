<?php

namespace Setlist\Infrastructure\Repository\Application\PDO;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistCollection;
use Setlist\Application\Persistence\Setlist\PersistedSetlistRepository as ApplicationSetlistRepositoryInterface;
use Setlist\Application\Persistence\Song\PersistedSongCollectionFactory;
use Setlist\Application\Persistence\Song\PersistedSongRepository as ApplicationSongRepository;
use PDO;

class PersistedSetlistRepository implements ApplicationSetlistRepositoryInterface
{
    private $PDO;
    private $songRepository;
    private $persistedSongCollectionFactory;

    use PDOHelper;

    public function __construct(
        PDO $PDO,
        ApplicationSongRepository $songRepository,
        PersistedSongCollectionFactory $persistedSongCollectionFactory
    ) {
        $this->PDO = $PDO;
        $this->songRepository = $songRepository;
        $this->persistedSongCollectionFactory = $persistedSongCollectionFactory;
    }

    public function getOneSetlistById(string $id): ?PersistedSetlist
    {
        $sql = <<<SQL
SELECT * FROM `setlist` WHERE `id` = :uuid;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $id);
        $query->execute();

        $returnedSetlist = $query->fetch(PDO::FETCH_ASSOC);

        if ($returnedSetlist) {
            return $this->getSetlistFromData($returnedSetlist, true);
        }

        return null;
    }

    public function getAllSetlists(int $start, int $length, string $name): PersistedSetlistCollection
    {
        $sql = <<<SQL
SELECT * FROM `setlist` %s ORDER BY `name` ASC, `creation_date` ASC%s;
SQL;

        $filterByNameString = $this->getFilterByNameString($name);
        $limitString = $this->getLimitString($start, $length);
        $sql = sprintf($sql, $filterByNameString, $limitString);

        $query = $this->PDO->prepare($sql);
        $query->execute();
        $returnedSetlists = $this->PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $setlistsForCollection = [];
        foreach ($returnedSetlists as $returnedSetlist) {
            $setlistsForCollection[] = $this->getSetlistFromData($returnedSetlist, true);
        }

        return PersistedSetlistCollection::create(...$setlistsForCollection);
    }

    public function getSetlistsInfoBySongId(string $id): PersistedSetlistCollection
    {
        $sql = <<<SQL
SELECT `id`, `name`, `description`, `date`, `creation_date`, `update_date` FROM `setlist` 
INNER JOIN `setlist_song` ON `setlist`.`id` = `setlist_song`.`setlist_id`
WHERE `setlist_song`.`song_id` = :uuid
ORDER BY `name` ASC, `creation_date` ASC;
SQL;

        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $id);
        $query->execute();

        $returnedSetlists = $query->fetchAll(PDO::FETCH_ASSOC);

        $setlistsForCollection = [];
        foreach ($returnedSetlists as $returnedSetlist) {
            $setlistsForCollection[] = $this->getSetlistFromData($returnedSetlist, false);
        }

        return PersistedSetlistCollection::create(...$setlistsForCollection);
    }
}
