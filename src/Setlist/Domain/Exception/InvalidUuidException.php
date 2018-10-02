<?php

namespace Setlist\Domain\Exception;

class InvalidUuidException extends \Exception
{
    public $message = 'Invalid identifier';
}
