<?php

namespace Setlist\Application\Exception;

class InvalidSetlistException extends ApplicationException
{
    public $message = 'Invalid setlist provided';
}