<?php

namespace Setlist\Infrastructure\Repository\Application\PDO;

use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepositoryInterface;
use Setlist\Domain\Entity\Setlist\ActFactory;
use \Setlist\Domain\Entity\Song\SongRepository as DomainSongRepositoryInterface;
use PDO;
use Setlist\Domain\Entity\Setlist\SetlistCollection;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Value\Uuid;

class SetlistRepository implements ApplicationSetlistRepositoryInterface
{
    private $PDO;
    private $setlistFactory;
    private $songRepository;
    private $actFactory;

    use PDOHelper;

    public function __construct(
        PDO $PDO,
        SetlistFactory $setlistFactory,
        DomainSongRepositoryInterface $songRepository,
        ActFactory $actFactory
    ) {
        $this->PDO = $PDO;
        $this->setlistFactory = $setlistFactory;
        $this->songRepository = $songRepository;
        $this->actFactory = $actFactory;
    }

    public function getAllNames(): array
    {
        $sql = <<<SQL
SELECT `name` FROM `setlist`;
SQL;
        return $this->PDO->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getOtherNames(string $uuid): array
    {
        $sql = <<<SQL
SELECT `name` FROM `setlist` WHERE `id` != :uuid;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllSetlists(int $start, int $length): SetlistCollection
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
            $sql = <<<SQL
SELECT * FROM `setlist_song` WHERE setlist_id = :uuid ORDER BY act, `order`;
SQL;
            $query = $this->PDO->prepare($sql);
            $query->bindValue('uuid', $returnedSetlist['id']);
            $query->execute();
            $setlistSongs = $query->fetchAll(PDO::FETCH_ASSOC);

            $currentAct = 0;
            $acts =
            $actsForSetlist = [];
            foreach ($setlistSongs as $song) {
                if ($song['act'] != $currentAct) {
                    $currentAct = $song['act'];
                }

                $acts[$currentAct][$song['order']] = $this->songRepository->get(Uuid::create($song['song_id']));
            }

            foreach ($acts as $act) {
                $actsForSetlist[] = $this->actFactory->make($act);
            }

            $setlistsForCollection[] = $this->setlistFactory->restore(
                $returnedSetlist['id'],
                $actsForSetlist,
                $returnedSetlist['name'],
                $returnedSetlist['date'],
                $returnedSetlist['creation_date'],
                $returnedSetlist['update_date']
            );
        }

        return SetlistCollection::create(...$setlistsForCollection);
    }

    public function getSetlistSongsCount(string $uuid): int
    {
        return 0;
    }
}
