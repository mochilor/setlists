<?php

namespace Setlist\Infrastructure\Repository\Application\PDO;

use Setlist\Application\Persistence\Song\PersistedSong;
use Setlist\Application\Persistence\Song\PersistedSongCollection;
use Setlist\Application\Persistence\Song\PersistedSongRepository as ApplicationSongRepositoryInterface;
use PDO;

class PersistedSongRepository implements ApplicationSongRepositoryInterface
{
    private $PDO;

    use PDOHelper;

    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function getOneSongById(string $id): ?PersistedSong
    {
        $sql = <<<SQL
SELECT * FROM `song` WHERE `id` = :uuid;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $id);
        $query->execute();

        $returnedSong = $query->fetch(PDO::FETCH_ASSOC);

        if ($returnedSong) {
            return $this->getPersistedSong($returnedSong);
        }

        return null;
    }

    public function getAllSongs(int $start, int $length): PersistedSongCollection
    {
        $sql = <<<SQL
SELECT * FROM `song` ORDER BY `creation_date` ASC%s;
SQL;
        $limitString = $this->getLimitString($start, $length);
        $sql = sprintf($sql, $limitString);

        $query = $this->PDO->prepare($sql);
        $query->execute();
        $songs = $this->PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $songsArray = [];
        foreach ($songs as $songData) {
            $songsArray[] = $this->getPersistedSong($songData);
        }

        return PersistedSongCollection::create(...$songsArray);
    }

    public function getSongsByTitle(string $title): PersistedSongCollection
    {
        $sql = <<<SQL
SELECT * FROM `song` WHERE `title` LIKE "%%%s%%" ORDER BY `creation_date` ASC;
SQL;
        $sql = sprintf($sql, $title);

        $query = $this->PDO->prepare($sql);
        $query->execute();
        $songs = $this->PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $songsArray = [];
        foreach ($songs as $songData) {
            $songsArray[] = $this->getPersistedSong($songData);
        }

        return PersistedSongCollection::create(...$songsArray);
    }

    private function getPersistedSong($songData): PersistedSong
    {
        return new PersistedSong(
            $songData['id'],
            $songData['title'],
            $songData['is_visible'],
            $songData['creation_date'],
            $songData['update_date']
        );
    }
}
