<?php

namespace Setlist\Infrastructure\Repository\Domain\PDO;

use PDO;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Value\Uuid;

trait PDOHelper
{
    public function getLimitString(int $start, int $length): string
    {
        $limitString = '';
        if ($length > 0) {
            $limitString = ' LIMIT ';
            if ($start > 0) {
                $limitString .= $start . ', ';
            }
            $limitString .= $length;
        }

        return $limitString;
    }

    public function getSetlistFromData($setlistData): Setlist
    {
        $sql = <<<SQL
SELECT * FROM `setlist_song` WHERE setlist_id = :uuid ORDER BY act, `order`;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $setlistData['id']);
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

        return $this->setlistFactory->restore(
            $setlistData['id'],
            $actsForSetlist,
            $setlistData['name'],
            $setlistData['date'],
            $setlistData['creation_date'],
            $setlistData['update_date']
        );
    }
}
