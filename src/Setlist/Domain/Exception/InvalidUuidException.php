<?php

namespace Setlist\Domain\Exception;

class InvalidUuidException extends DomainException
{
    public $message = 'Invalid identifier';
}
