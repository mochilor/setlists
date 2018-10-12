<?php

namespace Setlist\Infrastructure\Persistence\PDO;

use Setlist\Application\Persistence\Setlist\ApplicationSetlistRepository as ApplicationSetlistRepositoryInterface;
use PDO;

class ApplicationSetlistRepository implements ApplicationSetlistRepositoryInterface
{
    private $PDO;

    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;
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


}
