<?php

namespace Setlist\Infrastructure\Repository\Domain\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'update_date';

    public $incrementing = false;

    protected $keyType = 'string';
    protected $table = 'song';
    protected $guarded = [];
}
