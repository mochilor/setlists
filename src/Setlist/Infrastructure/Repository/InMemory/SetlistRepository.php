<?php

namespace Setlist\Infrastructure\Repository\InMemory;

use Setlist\Domain\Entity\Setlist\Event\SetlistWasCreated;
use Setlist\Domain\Entity\Setlist\Setlist;
use Setlist\Domain\Entity\Setlist\SetlistFactory;
use Setlist\Domain\Entity\Setlist\SetlistRepository as SetlistRepositoryInterface;
use Setlist\Domain\Value\Uuid;

class SetlistRepository implements SetlistRepositoryInterface
{
    private $setlistFactory;
    private $setlists = [];

    public function __construct(SetlistFactory $setlistFactory)
    {
        $this->setlistFactory = $setlistFactory;
    }

    public function nextIdentity(): Uuid
    {
        return Uuid::random();
    }

    public function save(Setlist $setlist)
    {
        $events = $setlist->events();

        foreach ($events as $event) {
            $this->runQuery($event);
        }
    }

    public function get(Uuid $uuid): ?Setlist
    {
        foreach ($this->setlists as $setlist) {
            if ($setlist['uuid'] == $uuid) {
                return $setlist;
            }
        }

        return null;
    }

    private function runQuery($event)
    {
        switch (get_class($event)) {
            case SetlistWasCreated::class:
                $this->insert($event->id(), $event->title());
                break;
        }
    }

    private function insert(string $uuid, string $title)
    {
        $setlist = [
            'uuid' => $uuid,
            'name' => $title,
        ];

        $this->setlists[] = $setlist;
    }
}
