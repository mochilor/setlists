<?php

namespace Setlist\Infrastructure\Repository\Application\PDO;

use Setlist\Application\Persistence\Setlist\SetlistRepository as ApplicationSetlistRepositoryInterface;
use PDO;

class SetlistRepository implements ApplicationSetlistRepositoryInterface
{
    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function getSelistsCountBySongId(string $id): int
    {
        $sql = <<<SQL
SELECT count(*) FROM setlist
INNER JOIN setlist_song ON setlist.id =  setlist_song.setlist_id
WHERE setlist_song.song_id = :uuid;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $id);
        $query->execute();

        $count = (int)$query->fetchColumn();

        return $count;
    }
}
