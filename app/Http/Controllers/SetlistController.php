<?php

namespace App\Http\Controllers;

use App\Http\Payload\DeleteSetlistPayload;
use App\Http\Payload\UpdateSetlistPayload;
use Setlist\Application\Command\Setlist\CreateSetlist;
use App\Http\Payload\CreateSetlistPayload;
use Setlist\Application\Command\Setlist\DeleteSetlist;
use Setlist\Application\Command\Setlist\UpdateSetlist;

class SetlistController extends Controller
{
    public function createSetlist(CreateSetlistPayload $createSetlistPayload)
    {
        $command = $this->messageFactory->makeCommand(CreateSetlist::class, $createSetlistPayload());

        return $this->dispatchCommand($command, 'New Setlist inserted');
    }

    public function updateSetlist(UpdateSetlistPayload $updateSetlistPayload)
    {
        $command = $this->messageFactory->makeCommand(UpdateSetlist::class, $updateSetlistPayload());

        return $this->dispatchCommand($command, 'Setlist updated');
    }

    public function deleteSetlist(DeleteSetlistPayload $deleteSetlistPayload)
    {
        $command = $this->messageFactory->makeCommand(DeleteSetlist::class, $deleteSetlistPayload());

        return $this->dispatchCommand($command, 'Setlist deleted');
    }
}
