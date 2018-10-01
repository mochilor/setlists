<?php

namespace Setlist\Infrastructure\Persistence\PDO;

use Setlist\Application\Persistence\Song\ApplicationSongRepository as ApplicationSongRepositoryInterface;
use PDO;

class ApplicationSongRepository implements ApplicationSongRepositoryInterface
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
}
