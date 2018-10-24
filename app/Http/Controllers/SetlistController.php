<?php

namespace App\Http\Controllers;

use App\Http\Payload\DeleteSetlistPayload;
use App\Http\Payload\GetSetlistPayload;
use App\Http\Payload\GetSetlistsPayload;
use App\Http\Payload\UpdateSetlistPayload;
use Setlist\Application\Command\Setlist\CreateSetlist;
use App\Http\Payload\CreateSetlistPayload;
use Setlist\Application\Command\Setlist\DeleteSetlist;
use Setlist\Application\Command\Setlist\UpdateSetlist;
use Setlist\Application\Query\Setlist\GetSetlist;
use Setlist\Application\Query\Setlist\GetSetlists;

class SetlistController extends Controller
{
    public function createSetlist(CreateSetlistPayload $createSetlistPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($createSetlistPayload, CreateSetlist::class),
            'New Setlist inserted'
        );
    }

    public function updateSetlist(UpdateSetlistPayload $updateSetlistPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($updateSetlistPayload, UpdateSetlist::class),
            'Setlist updated'
        );
    }

    public function deleteSetlist(DeleteSetlistPayload $deleteSetlistPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($deleteSetlistPayload, DeleteSetlist::class),
            'Setlist deleted'
        );
    }

    public function getSetlist(GetSetlistPayload $getSetlistPayload)
    {
        return $this->dispatchQuery(
            $this->getQuery($getSetlistPayload, GetSetlist::class)
        );
    }

    public function getSetlists(GetSetlistsPayload $getSetlistPayload)
    {
        return $this->dispatchQuery(
            $this->getQuery($getSetlistPayload, GetSetlists::class)
        );
    }
}
