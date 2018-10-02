<?php

namespace Setlist\Domain\Exception\Song;

class InvalidSongTitleException extends \Exception
{
    public $message = 'Invalid song title';
}
