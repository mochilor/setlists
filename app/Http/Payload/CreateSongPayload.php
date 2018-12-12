<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class CreateSongPayload
{
    private $title;
    private $uuid;

    public function __construct(Request $request)
    {
        $this->uuid = $request->input('id', '');
        $this->title = $request->input('title', '');
    }

    public function __invoke()
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
        ];
    }
}