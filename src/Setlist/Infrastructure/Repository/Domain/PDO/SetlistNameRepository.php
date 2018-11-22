<?php

namespace Setlist\Infrastructure\Repository\Domain\PDO;

use PDO;
use Setlist\Domain\Entity\Setlist\SetlistNameRepository as SetlistNameRepositoryInterface;

class SetlistNameRepository implements SetlistNameRepositoryInterface
{
    private $PDO;

    public function __construct(PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function nameIsAvailable(string $name): bool
    {
        $sql = <<<SQL
SELECT * FROM `setlist` WHERE `name` = :name;
SQL;
        $query = $this->PDO->prepare($sql);
        $query->bindValue('name', $name);
        $query->execute();

        $setlist = $query->fetch(PDO::FETCH_ASSOC);

        return empty($setlist);
    }

    public function nameIsUnique(string $name, string $uuid): bool
    {
        $sql = <<<SQL
SELECT * FROM `setlist` WHERE `id` <> :uuid AND `name` = :name;
SQL;

        $query = $this->PDO->prepare($sql);
        $query->bindValue('name', $name);
        $query->bindValue('uuid', $uuid);
        $query->execute();

        $setlistsArray = $query->fetchAll(PDO::FETCH_ASSOC);

        return empty($setlistsArray);
    }
}
