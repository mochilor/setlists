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
    /**
     * @OA\Post(
     *     path="/api/setlist",
     *     tags={"Setlists"},
     *     description="Creates a setlist with existing songs.",
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
     *                     property="name",
     *                     type="string",
     *                     description="The name of the setlist",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="An optional description of the setlist",
     *                 ),
     *                 @OA\Property(
     *                     property="date",
     *                     type="string",
     *                     description="A date in format yyyy-mm-dd in wich is supposed to be the show.",
     *                 ),
     *                 @OA\Property(
     *                     property="acts[0][0]",
     *                     type="string",
     *                     description="This will add the first song for the first act. You must provide existing song uuids for the acts.",
     *                 ),
     *                 @OA\Property(
     *                     property="acts[0][1]",
     *                     type="string",
     *                     description="Same here. In a real use case, any number of acts and songs can be provided.",
     *                 ),
     *                 @OA\Property(
     *                     property="acts[1][0]",
     *                     type="string",
     *                     description="The first song of the second act.",
     *                 ),
     *                 @OA\Property(
     *                     property="acts[1][1]",
     *                     type="string",
     *                     description="The second song of the second act.",
     *                 ),
     *                 @OA\Property(
     *                     property="acts[1][2]",
     *                     type="string",
     *                     description="The third song of the second act. Please note that it's not mandatory to send all songs and acts in the exact order. 'Gaps' in the list are alllowed.",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="The setlist has been succesfully created.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Error: id or name already exists.",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: invalid identifier, invalid name, invalid id of a song, non existing song, repeated song in setlist.",
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
    public function createSetlist(CreateSetlistPayload $createSetlistPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($createSetlistPayload, CreateSetlist::class),
            'New Setlist inserted'
        );
    }

    /**
     * @OA\Patch(
     *     path="/api/setlist/{uuid}",
     *     tags={"Setlists"},
     *     description="Updates an existing setlist.",
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
     *         description="The uuid of the setlist.",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The name of the setlist",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="An optional description of the setlist",
     *                 ),
     *                 @OA\Property(
     *                     property="date",
     *                     type="string",
     *                     description="A date in format yyyy-mm-dd in wich is supposed to be the show.",
     *                 ),
     *                 @OA\Property(
     *                     property="acts[0][0]",
     *                     type="string",
     *                     description="This will add the first song for the first act. You must provide existing song uuids for the acts.",
     *                 ),
     *                 @OA\Property(
     *                     property="acts[0][1]",
     *                     type="string",
     *                     description="Same here. In a real use case, any number of acts and songs can be provided.",
     *                 ),
     *                 @OA\Property(
     *                     property="acts[1][0]",
     *                     type="string",
     *                     description="The first song of the second act.",
     *                 ),
     *                 @OA\Property(
     *                     property="acts[1][1]",
     *                     type="string",
     *                     description="The second song of the second act.",
     *                 ),
     *                 @OA\Property(
     *                     property="acts[1][2]",
     *                     type="string",
     *                     description="The third song of the second act. Please note that it's not mandatory to send all songs and acts in the exact order. 'Gaps' in the list are alllowed.",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The setlist has been succesfully updated.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="409",
     *         description="Error: name already exists in another stored setlist.",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: the requested setlist does not exist.",
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error: invalid identifier, invalid name, invalid id of a song, non existing song, repeated song in setlist.",
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
    public function updateSetlist(UpdateSetlistPayload $updateSetlistPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($updateSetlistPayload, UpdateSetlist::class),
            'Setlist updated'
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/setlist/{uuid}",
     *     tags={"Setlists"},
     *     description="Deletes an existing setlist. The related songs remains.",
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
     *         description="The uuid of the setlist.",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The setlist has been succesfully deleted.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: the requested setlist does not exist.",
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
    public function deleteSetlist(DeleteSetlistPayload $deleteSetlistPayload)
    {
        return $this->dispatchCommand(
            $this->getCommand($deleteSetlistPayload, DeleteSetlist::class),
            'Setlist deleted'
        );
    }

    /**
     * @OA\Get(
     *     path="/api/setlist/{uuid}",
     *     tags={"Setlists"},
     *     description="Returns an existing setlist with all its acts and songs.",
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
     *         description="The uuid of the setlist.",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A setlist with all its attributes.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Error: the requested setlist does not exist.",
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
    public function getSetlist(GetSetlistPayload $getSetlistPayload)
    {
        return $this->dispatchQuery(
            $this->getQuery($getSetlistPayload, GetSetlist::class)
        );
    }

    /**
     * @OA\Get(
     *     path="/api/setlists",
     *     tags={"Setlists"},
     *     description="Returns all stored setlists (with all its acts and songs) or a range of them, if the optional parameter 'interval' was provided. Filtering by setlist name is also possible.",
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
     *         description="The offset and limit of the requested setlist collection, separated with a comma.",
     *         allowReserved="true",
     *         @OA\Examples(
     *              value="0,1",
     *              summary="Getting only the first setlist",
     *         ),
     *         @OA\Examples(
     *              value="10,50",
     *              summary="A list of setlists, starting in the 11th and with a length of 50 setlists.",
     *         ),
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         style="form",
     *         explode="false",
     *         description="A string to filter the name of the setlists in the collection. The comparison is case insensitive.",
     *         allowReserved="true",
     *         @OA\Examples(
     *              value="example",
     *              summary="Getting only those setlists with the string 'example' in their names.",
     *         ),
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="A collection of setlists with all their attributes, according to the 'interval' and 'name' parameters, if provided.",
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
    public function getSetlists(GetSetlistsPayload $getSetlistPayload)
    {
        return $this->dispatchQuery(
            $this->getQuery($getSetlistPayload, GetSetlists::class)
        );
    }
}
