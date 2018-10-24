<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class GetSongPayload
{
    private $uuid;

    public function __construct(Request $request)
    {
        $idParameter = $request->route()[2];
        $this->uuid = $idParameter['id'];
    }

    public function __invoke()
    {
        return [
            'uuid' => $this->uuid,
        ];
    }
}
