<?php

namespace Setlist\Application\Exception;

class SetlistNameNotUniqueException extends EntityAlreadyExistsException
{
    public $message = 'There is already a Setlist with this name';
}