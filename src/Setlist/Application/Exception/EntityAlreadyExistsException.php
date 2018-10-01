<?php

namespace Setlist\Application\Exception;

class EntityAlreadyExistsException extends \Exception
{
    public $message = 'Element already exists';
}