<?php

namespace Setlist\Infrastructure\DataTransformer;

use Setlist\Application\Persistence\Setlist\PersistedSetlist;
use Setlist\Application\DataTransformer\SetlistDataTransformer as SetlistDataTransformerInterface;
use Setlist\Application\DataTransformer\SongDataTransformer as SongDataTransformerInterface;

class SetlistDataTransformer implements SetlistDataTransformerInterface
{
    private $setlist;
    private $songDataTransformer;

    public function __construct(SongDataTransformerInterface $songDataTransformer)
    {
        $this->songDataTransformer = $songDataTransformer;
    }

    public function write(PersistedSetlist $setlist)
    {
        $this->setlist = $setlist;
    }

    public function read(): array
    {
        $acts = [];
        foreach ($this->setlist->acts() as $songCollection) {
            $currentAct = [];
            foreach ($songCollection as $song) {
                $this->songDataTransformer->write($song);
                $currentAct[] = $this->songDataTransformer->read();
            }
            $acts[] = $currentAct;
        }

        return [
            'id' => (string) $this->setlist->id(),
            'name' => $this->setlist->name(),
            'date' => $this->setlist->date(),
            'acts' => $acts,
            'creation_date' => $this->setlist->creationDate(),
            'update_date' => $this->setlist->updateDate(),
        ];
    }
}
