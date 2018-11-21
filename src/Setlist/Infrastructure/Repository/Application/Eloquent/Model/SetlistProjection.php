<?php

namespace Setlist\Infrastructure\Repository\Application\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class SetlistProjection extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';
    protected $table = 'setlist_projection';
    protected $guarded = [];
}
