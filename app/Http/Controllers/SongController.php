<?php

namespace App\Http\Controllers;

use App\Http\Payload\DeleteSongPayload;
use App\Http\Payload\GetSongPayload;
use App\Http\Payload\GetSongsByTitlePayload;
use App\Http\Payload\GetSongsPayload;
use App\Http\Payload\UpdateSongPayload;
use Setlist\Application\Command\Song\CreateSong;
use App\Http\Payload\CreateSongPayload;
use Setlist\Application\Command\Song\DeleteSong;
use Setlist\Application\Command\Song\ForceDeleteSong;
use Setlist\Application\Command\Song\UpdateSong;
use Setlist\Application\Query\Song\GetSong;
use Setlist\Application\Query\Song\GetSongs;
use Setlist\Application\Query\Song\GetSongsByTitle;

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

    public function forceDeleteSong(DeleteSongPayload $deleteSongPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($deleteSongPayload, ForceDeleteSong::class),
            'Song deleted'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/song/{song}",
     *     tags={"Songs"},
     *     description="Returns a song",
     *     @OA\Parameter(
     *         name="song",
     *         in="path",
     *         description="The uuid of the song",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A song with all its attributes",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: invalid identifier",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: the requested song does not exist.",
     *     ),
     * )
     */
    public function getSong(GetSongPayload $getSongPayload)
    {
        return $this->dispatchQuery(
            $this->getQuery($getSongPayload, GetSong::class)
        );
    }

    /**
     * @OA\Get(
     *     path="/api/songs",
     *     tags={"Songs"},
     *     description="Returns all stored songs or a range of them, if the optional parameter 'interval' was provided",
     *     @OA\Parameter(
     *         name="interval",
     *         in="query",
     *         style="form",
     *         explode="false",
     *         description="The offset and limit of the requested songs collection, separated with a comma",
     *         allowReserved="true",
     *         @OA\Examples(
     *              value="0,1",
     *              summary="Getting only the first song",
     *         ),
     *         @OA\Examples(
     *              value="10,50",
     *              summary="A list of songs, starting in the 11th song and with a length of 50 songs",
     *         ),
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A collection of songs with all their attributes, according to the 'interval' parameter, if provided",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function getSongs(GetSongsPayload $getSongsPayload)
    {
        return $this->dispatchQuery(
            $this->getQuery($getSongsPayload, GetSongs::class)
        );
    }
}
