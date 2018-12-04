<?php

namespace Setlist\Infrastructure\Repository\Application\PDO;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\Persistence\Setlist\PersistedSetlistCollection;
use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepositoryInterface;
use Setlist\Application\Persistence\Song\PersistedSongCollectionFactory;
use Setlist\Application\Persistence\Song\SongRepository as ApplicationSongRepository;
use PDO;

class SetlistRepository implements ApplicationSetlistRepositoryInterface
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
            return $this->getSetlistFromData($returnedSetlist);
        }

        return null;
    }

    public function getAllSetlists(int $start, int $length): PersistedSetlistCollection
    {
        $sql = <<<SQL
SELECT * FROM `setlist` ORDER BY `creation_date` ASC%s;
SQL;
        $limitString = $this->getLimitString($start, $length);
        $sql = sprintf($sql, $limitString);

        $query = $this->PDO->prepare($sql);
        $query->execute();
        $returnedSetlists = $this->PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $setlistsForCollection = [];
        foreach ($returnedSetlists as $returnedSetlist) {
            $setlistsForCollection[] = $this->getSetlistFromData($returnedSetlist);
        }

        return PersistedSetlistCollection::create(...$setlistsForCollection);
    }
}
