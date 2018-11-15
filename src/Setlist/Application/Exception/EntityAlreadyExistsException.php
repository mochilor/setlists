<?php

namespace Setlist\Application\Exception;

class EntityAlreadyExistsException extends ApplicationException
{
    public $message = 'Element already exists';
    public $code = 409;
}