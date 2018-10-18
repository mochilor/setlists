<?php

namespace Setlist\Infrastructure\Repository\Application\PDO;

use Setlist\Application\Persistence\Song\SongRepository as ApplicationSongRepositoryInterface;
use PDO;

class SongRepository implements ApplicationSongRepositoryInterface
{
    private $PDO;

    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;
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
}
