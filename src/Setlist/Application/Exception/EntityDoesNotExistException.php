<?php

namespace Setlist\Application\Exception;

class EntityDoesNotExistException extends \Exception
{
    public $message = 'Element does not exist';
    public $code = 404;
}
