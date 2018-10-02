<?php

namespace App\Http\Controllers;

use App\Http\Payload\DeleteSongPayload;
use App\Http\Payload\UpdateSongPayload;
use Setlist\Application\Command\CreateSong;
use App\Http\Payload\CreateSongPayload;
use Setlist\Application\Command\DeleteSong;
use Setlist\Application\Command\UpdateSong;

class SongController extends Controller
{
    public function createSong(CreateSongPayload $createSongPayload)
    {
        $command = $this->messageFactory->make(CreateSong::class, $createSongPayload());

        return $this->dispatchCommand($command, 'New song inserted');
    }

    public function updateSong(UpdateSongPayload $updateSongPayload)
    {
        $command = $this->messageFactory->make(UpdateSong::class, $updateSongPayload());

        return $this->dispatchCommand($command, 'Song updated');
    }

    public function deleteSong(DeleteSongPayload $deleteSongPayload)
    {
        $command = $this->messageFactory->make(DeleteSong::class, $deleteSongPayload());

        return $this->dispatchCommand($command, 'Song deleted');
    }
}
