<?php

namespace Setlist\Domain\Service\Setlist;

class SetlistNameValidator
{
    private $names;

    public static function create(array $names): self
    {
        $setlistNameValidator = new self();
        $setlistNameValidator->names = $names;

        return $setlistNameValidator;
    }

    public function setlistNameIsUnique(string $name): bool
    {
        return !in_array($name, $this->names);
    }
}
