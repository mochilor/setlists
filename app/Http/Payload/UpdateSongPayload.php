<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class UpdateSongPayload
{
    private $title;
    private $uuid;

    public function __construct(Request $request)
    {
        $this->uuid = $request->route()[2]['id'];
        $this->title = $request->input('title', '');
    }

    public function __invoke()
    {
        return [
            'title' => $this->title,
            'uuid' => $this->uuid,
        ];
    }
}