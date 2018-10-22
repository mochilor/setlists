<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class DeleteSetlistPayload
{
    private $uuid;

    public function __construct(Request $request)
    {
        $this->uuid = $request->route()[2]['id'];
    }

    public function __invoke()
    {
        return [
            'uuid' => $this->uuid,
        ];
    }
}