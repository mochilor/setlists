<?php

namespace Setlist\Application\Persistence\Setlist;

use Setlist\Application\Persistence\Song\PersistedSongCollection;

class PersistedSetlist
{
    private $id;
    private $acts;
    private $name;
    private $date;
    private $description;
    private $creationDate;
    private $updateDate;

    public function __construct(
        string $id,
        array $acts,
        string $name,
        string $description,
        string $date,
        string $creationDate,
        string $updateDate
    ) {
        $this->id = $id;
        $this->setActs($acts);
        $this->name = $name;
        $this->description = $description;
        $this->date = $date;
        $this->creationDate = $creationDate;
        $this->updateDate = $updateDate;
    }

    private function setActs(array $acts)
    {
        foreach ($acts as $key => $act) {
            if (!$act instanceof PersistedSongCollection) {
                unset($acts[$key]);
            }
        }

        $this->acts = $acts;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function acts(): array
    {
        return $this->acts;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function date(): string
    {
        return $this->date;
    }

    public function creationDate(): string
    {
        return $this->creationDate;
    }

    public function updateDate(): string
    {
        return $this->updateDate;
    }
}
