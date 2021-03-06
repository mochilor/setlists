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

    public function getFilterByTitleString(string $title, string $whereNotInString): string
    {
        if (!empty($title)) {
            $whereString = $whereNotInString ? 'AND' : 'WHERE';
            return " $whereString `title` LIKE '%$title%'";
        }

        return '';
    }

    public function getFilterByNameString(string $name): string
    {
        if (!empty($name)) {
            return " WHERE `name` LIKE '%$name%'";
        }

        return '';
    }

    public function getWhereNotInString(string $notIn)
    {
        if (!empty($notIn)) {
            $string = <<<SQL
 LEFT JOIN `setlist_song` ON `song`.id = `setlist_song`.song_id 
WHERE (`setlist_song`.setlist_id != '%s'
OR `setlist_song`.setlist_id is null)
SQL;
            return sprintf($string, $notIn);
        }

        return '';
    }

    public function getSetlistFromData(array $setlistData, bool $withSongs): PersistedSetlist
    {
        $persistedSongCollections = [];
        if ($withSongs) {
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

            foreach ($acts as $act) {
                $persistedSongCollections[] = $this->persistedSongCollectionFactory->make($act);
            }
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
