<?php

namespace Setlist\Infrastructure\Repository\Application\PDO;

use Setlist\Application\Persistence\Song\SongRepository as ApplicationSongRepositoryInterface;
use PDO;
use Setlist\Domain\Entity\Setlist\SongCollection;
use Setlist\Domain\Entity\Song\SongFactory;
use Setlist\Infrastructure\Repository\Domain\PDO\PDOHelper;

class SongRepository implements ApplicationSongRepositoryInterface
{
    private $PDO;
    private $songFactory;

    use PDOHelper;

    public function __construct(PDO $PDO, SongFactory $songFactory)
    {
        $this->PDO = $PDO;
        $this->songFactory = $songFactory;
    }

    public function getAllTitles(): array
    {
        $sql = <<<SQL
SELECT `title` FROM `song`;
SQL;
        return $this->PDO->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getOtherTitles(string $uuid): array
    {
        $sql = <<<SQL
SELECT `title` FROM `song` WHERE `id` != :uuid;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('uuid', $uuid);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllSongs(int $start, int $length): SongCollection
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
            $songsArray[] = $this->songFactory->restore(
                $songData['id'],
                $songData['title'],
                $songData['creation_date'],
                $songData['update_date']
            );
        }

        return SongCollection::create(...$songsArray);
    }
}
