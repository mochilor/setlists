<?php

namespace Setlist\Infrastructure\Repository\Domain\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

class Setlist extends Model
{
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'update_date';

    public $incrementing = false;

    protected $keyType = 'string';
    protected $table = 'setlist';
    protected $guarded = [];

    public function songs()
    {
        return $this->belongsToMany(Song::class, 'setlist_song')->withPivot('act', 'order');
    }
}
