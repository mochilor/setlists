<?php

namespace App\Http\Controllers;

//use App\Http\Payload\DeleteSetlistPayload;
//use App\Http\Payload\UpdateSetlistPayload;
use Setlist\Application\Command\CreateSetlist;
use App\Http\Payload\CreateSetlistPayload;
//use Setlist\Application\Command\DeleteSetlist;
//use Setlist\Application\Command\UpdateSetlist;

class SetlistController extends Controller
{
    public function createSetlist(CreateSetlistPayload $createSetlistPayload)
    {
        $command = $this->messageFactory->make(CreateSetlist::class, $createSetlistPayload());

        return $this->dispatchCommand($command, 'New Setlist inserted');
    }
//
//    public function updateSetlist(UpdateSetlistPayload $updateSetlistPayload)
//    {
//        $command = $this->messageFactory->make(UpdateSetlist::class, $updateSetlistPayload());
//
//        return $this->dispatchCommand($command, 'Setlist updated');
//    }
//
//    public function deleteSetlist(DeleteSetlistPayload $deleteSetlistPayload)
//    {
//        $command = $this->messageFactory->make(DeleteSetlist::class, $deleteSetlistPayload());
//
//        return $this->dispatchCommand($command, 'Setlist deleted');
//    }
}
