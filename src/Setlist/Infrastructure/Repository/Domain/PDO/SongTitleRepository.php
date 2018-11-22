<?php

namespace Setlist\Infrastructure\Repository\Domain\PDO;

use PDO;
use Setlist\Domain\Entity\Song\SongTitleRepository as SongTitleRepositoryInterface;

class SongTitleRepository implements SongTitleRepositoryInterface
{
    private $PDO;

    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function titleIsAvailable(string $title): bool
    {
        $sql = <<<SQL
SELECT * FROM `song` WHERE `title` = :title;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('title', $title);
        $query->execute();

        $song = $query->fetch(PDO::FETCH_ASSOC);

        return empty($song);
    }

    public function titleIsUnique(string $title, string $uuid): bool
    {
        $sql = <<<SQL
SELECT * FROM `song` WHERE `id` = :uuid AND `title` = :title;
SQL;

        $query = $this->PDO->prepare($sql);
        $query->bindValue('title', $title);
        $query->bindValue('uuid', $uuid);
        $query->execute();

        $songsArray = $this->PDO->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return empty($songsArray);
    }
}
