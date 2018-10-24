<?php

namespace Setlist\Application\DataTransformer;

use Setlist\Domain\Entity\Setlist\Setlist;

class SetlistDataTransformer
{
    private $setlist;
    private $songDataTransformer;

    public function __construct(SongDataTransformer $songDataTransformer)
    {
        $this->songDataTransformer = $songDataTransformer;
    }

    public function write(Setlist $setlist)
    {
        $this->setlist = $setlist;
    }

    public function read(): array
    {
        $acts = [];
        foreach ($this->setlist->actCollection() as $act) {
            $currentAct = [];
            foreach ($act->songCollection() as $song) {
                $this->songDataTransformer->write($song);
                $currentAct[] = $this->songDataTransformer->read();
            }
            $acts[] = $currentAct;
        }

        return [
            'id' => (string) $this->setlist->id(),
            'name' => $this->setlist->name(),
            'date' => $this->setlist->formattedDate(),
            'acts' => $acts,
            'creation_date' => $this->setlist->formattedCreationDate(),
            'update_date' => $this->setlist->formattedUpdateDate(),
        ];
    }
}