<?php

namespace Setlist\Infrastructure\Lumen\Http\Controllers;

use Setlist\Application\Command\CreateSong;
use Setlist\Infrastructure\Lumen\Http\Payload\CreateSongPayload;

class SongController extends Controller
{
    public function index()
    {
        echo 'hola!';
    }

    public function createSong(CreateSongPayload $createSongPayload)
    {
        $command = $this->messageFactory->make(
            CreateSong::class,
            ['payload' => $createSongPayload()]
        );

        return $this->dispatchCommand($command, 'New song inserted');
    }
}
