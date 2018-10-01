<?php

namespace App\Http\Controllers;

use Setlist\Application\Command\CreateSong;
use App\Http\Payload\CreateSongPayload;

class SongController extends Controller
{
    public function index()
    {
        echo 'hola!';
    }

    public function createSong(CreateSongPayload $createSongPayload)
    {
        $command = $this->messageFactory->make(CreateSong::class, $createSongPayload());

        return $this->dispatchCommand($command, 'New song inserted');
    }
}
