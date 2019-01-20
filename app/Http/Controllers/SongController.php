<?php

namespace App\Http\Controllers;

use App\Http\Payload\DeleteSongPayload;
use App\Http\Payload\GetSongPayload;
use App\Http\Payload\GetSongsPayload;
use App\Http\Payload\UpdateSongPayload;
use Setlist\Application\Command\Song\CreateSong;
use App\Http\Payload\CreateSongPayload;
use Setlist\Application\Command\Song\DeleteSong;
use Setlist\Application\Command\Song\ForceDeleteSong;
use Setlist\Application\Command\Song\UpdateSong;
use Setlist\Application\Query\Song\GetSong;
use Setlist\Application\Query\Song\GetSongs;
use Setlist\Application\Query\Song\GetSongStats;

class SongController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/song",
     *     tags={"Songs"},
     *     description="Creates a song.",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="A valid and recent token.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="string",
     *                     description="A valid Version 4 Uuid",
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="The title of the song",
     *                 ),
     *                 example={"id": 10, "title": "Example Song"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="The song has been succesfully created.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Error: id or title already exists.",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: invalid identifier or invalid title.",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          ref="#/components/responses/expired"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          ref="#/components/responses/unauthorized"
     *     ),
     * )
     */
    public function createSong(CreateSongPayload $createSongPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($createSongPayload, CreateSong::class),
            'New song inserted'
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/song/{uuid}",
     *     tags={"Songs"},
     *     description="Updates the fields 'title' and 'visibility' from a song.",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="A valid and recent token.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="The uuid of the song.",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="The title of the song",
     *                 ),
     *                 @OA\Property(
     *                     property="visibility",
     *                     type="integer",
     *                     description="Hide or unhide the song.",
     *                 ),
     *                 example={"title": "Example Song", "visibility": 0}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The song has been succesfully updated.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: the requested song does not exist.",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: invalid identifier or invalid title.",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          ref="#/components/responses/expired"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          ref="#/components/responses/unauthorized"
     *     ),
     * )
     */
    public function updateSong(UpdateSongPayload $updateSongPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($updateSongPayload, UpdateSong::class),
            'Song updated'
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/song/{uuid}",
     *     tags={"Songs"},
     *     description="Deletes a song.",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="A valid and recent token.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="The uuid of the song.",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The song has been succesfully deleted.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: the requested song does not exist.",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: invalid identifier or the song is present in one or more setlists.",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          ref="#/components/responses/expired"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          ref="#/components/responses/unauthorized"
     *     ),
     * )
     */
    public function deleteSong(DeleteSongPayload $deleteSongPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($deleteSongPayload, DeleteSong::class),
            'Song deleted'
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/song/{uuid}/force",
     *     tags={"Songs"},
     *     description="Deletes a song even if it belongs to one or more setlists.",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="A valid and recent token.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="The uuid of the song.",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The song has been succesfully deleted.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: the requested song does not exist.",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: invalid identifier.",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          ref="#/components/responses/expired"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          ref="#/components/responses/unauthorized"
     *     ),
     * )
     */
    public function forceDeleteSong(DeleteSongPayload $deleteSongPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($deleteSongPayload, ForceDeleteSong::class),
            'Song deleted'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/song/{uuid}",
     *     tags={"Songs"},
     *     description="Returns a song.",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="A valid and recent token.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="The uuid of the song.",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A song with all its attributes.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: invalid identifier.",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: the requested song does not exist.",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          ref="#/components/responses/expired"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          ref="#/components/responses/unauthorized"
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
     *     description="Returns all stored songs or a range of them, if the optional parameter 'interval' was provided. Filtering by song title is also possible.",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="A valid and recent token.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="interval",
     *         in="query",
     *         style="form",
     *         explode="false",
     *         description="The offset and limit of the requested songs collection, separated with a comma.",
     *         allowReserved="true",
     *         @OA\Examples(
     *              value="0,1",
     *              summary="Getting only the first song",
     *         ),
     *         @OA\Examples(
     *              value="10,50",
     *              summary="A list of songs, starting in the 11th song and with a length of 50 songs.",
     *         ),
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         style="form",
     *         explode="false",
     *         description="A string to filter the title of the songs in the collection. The comparison is case insensitive.",
     *         allowReserved="true",
     *         @OA\Examples(
     *              value="example",
     *              summary="Getting only those songs with the string 'example' in their titles.",
     *         ),
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A collection of songs with all their attributes, according to the 'interval' and 'name' parameters, if provided.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *          response="400",
     *          ref="#/components/responses/expired"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          ref="#/components/responses/unauthorized"
     *     ),
     * )
     */
    public function getSongs(GetSongsPayload $getSongsPayload)
    {
        return $this->dispatchQuery(
            $this->getQuery($getSongsPayload, GetSongs::class)
        );
    }

    /**
     * @OA\Get(
     *     path="/api/song/stats/{uuid}",
     *     tags={"Songs"},
     *     description="Returns the setlist to which this song belongs.",
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="A valid and recent token.",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="The uuid of the song.",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A collection of setlists, without acts.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: invalid identifier.",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: the requested song does not exist.",
     *     ),
     *     @OA\Response(
     *          response="400",
     *          ref="#/components/responses/expired"
     *     ),
     *     @OA\Response(
     *          response="401",
     *          ref="#/components/responses/unauthorized"
     *     ),
     * )
     */
    public function getSongStats(GetSongPayload $getSongStatsPayload)
    {
        return $this->dispatchQuery(
            $this->getQuery($getSongStatsPayload, GetSongStats::class)
        );
    }
}
