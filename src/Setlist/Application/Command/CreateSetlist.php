<?php

namespace Setlist\Application\Command;

class CreateSetlist
{
    private $name;
    private $acts;
    private $dateTime;
    private $uuid;

    public static function create(string $uuid, string $name, array $acts, \DateTimeImmutable $dateTime)
    {
        $command = new self();
        $command->uuid = $uuid;
        $command->name = $name;
        $command->acts = $acts;
        $command->dateTime = $dateTime;

        return $command;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function acts(): array
    {
        return $this->acts;
    }

    public function dateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }
}
