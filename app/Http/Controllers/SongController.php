<?php

namespace App\Http\Controllers;

use App\Http\Payload\DeleteSongPayload;
use App\Http\Payload\GetSongPayload;
use App\Http\Payload\UpdateSongPayload;
use Setlist\Application\Command\Song\CreateSong;
use App\Http\Payload\CreateSongPayload;
use Setlist\Application\Command\Song\DeleteSong;
use Setlist\Application\Command\Song\UpdateSong;
use Setlist\Application\Query\Song\GetSong;

class SongController extends Controller
{
    public function createSong(CreateSongPayload $createSongPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($createSongPayload, CreateSong::class),
            'New song inserted'
        );
    }

    public function updateSong(UpdateSongPayload $updateSongPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($updateSongPayload, UpdateSong::class),
            'Song updated'
        );
    }

    public function deleteSong(DeleteSongPayload $deleteSongPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($deleteSongPayload, DeleteSong::class),
            'Song deleted'
        );
    }

    public function getSong(GetSongPayload $getSongPayload)
    {
        return $this->dispatchQuery(
            $this->getQuery($getSongPayload, GetSong::class)
        );
    }
}
