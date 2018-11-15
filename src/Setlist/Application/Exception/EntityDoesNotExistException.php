<?php

namespace Setlist\Application\Exception;

class EntityDoesNotExistException extends ApplicationException
{
    public $message = 'Element does not exist';
    public $code = 404;
}
