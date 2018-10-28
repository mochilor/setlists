<?php

namespace Setlist\Infrastructure\Repository\Domain\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistRepository as SetlistRepositoryInterface;
use Setlist\Domain\Value\Uuid;
use stdClass;

class SetlistRepository implements SetlistRepositoryInterface
{
    const TABLE_NAME = 'setlist';

    public function nextIdentity(): Uuid
    {
        return Uuid::random();
    }

    public function save(Setlist $setlist)
    {

    }

    public function get(Uuid $uuid)
    {
        $setlistData = $this->db()
            ->where('id', $uuid)
            ->first();

        if ($setlistData) {
            return $this->getSetlistFromData($setlistData);
        }

        return null;
    }

    private function db(): Builder
    {
        return app('db')->table(self::TABLE_NAME);
    }

    private function getSetlistFromData(stdClass $setlistData)
    {

    }
}
