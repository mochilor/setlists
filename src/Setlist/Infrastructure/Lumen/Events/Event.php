<?php

namespace Setlist\Infrastructure\Lumen\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;
}
