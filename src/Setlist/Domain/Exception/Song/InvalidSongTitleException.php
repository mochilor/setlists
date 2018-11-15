<?php

namespace Setlist\Domain\Exception\Song;

use Setlist\Domain\Exception\DomainException;

class InvalidSongTitleException extends DomainException
{
    public $message = 'Invalid song title';
}
