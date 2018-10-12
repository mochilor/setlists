<?php

namespace Setlist\Application\Exception;

class InvalidSetlistException extends \Exception
{
    public $message = 'Invalid setlist provided';
}