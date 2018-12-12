<?php

namespace Setlist\Infrastructure\Repository\Application\PDO;

use PDO;
use Setlist\Application\Persistence\Setlist\PersistedSetlist;

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

    public function getSetlistFromData($setlistData): PersistedSetlist
    {
        $sql = <<<SQL
SELECT * FROM `setlist_song` WHERE setlist_id = :uuid ORDER BY act, `order`;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $setlistData['id']);
        $query->execute();
        $setlistSongs = $query->fetchAll(PDO::FETCH_ASSOC);

        $currentAct = 0;
        $acts = [];
        foreach ($setlistSongs as $song) {
            if ($song['act'] != $currentAct) {
                $currentAct = $song['act'];
            }

            $acts[$currentAct][$song['order']] = $this->songRepository->getOneSongById($song['song_id']);
        }

        $persistedSongCollections = [];
        foreach ($acts as $act) {
            $persistedSongCollections[] = $this->persistedSongCollectionFactory->make($act);
        }

        return new PersistedSetlist(
            $setlistData['id'],
            $persistedSongCollections,
            $setlistData['name'],
            $setlistData['description'],
            $setlistData['date'],
            $setlistData['creation_date'],
            $setlistData['update_date']
        );
    }
}
