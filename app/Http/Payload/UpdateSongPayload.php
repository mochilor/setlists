<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class UpdateSongPayload
{
    private $title;
    private $uuid;
    private $visibility;

    public function __construct(Request $request)
    {
        $this->uuid = $request->route()[2]['id'];
        $this->title = $request->input('title', '');
        $this->visibility = $request->input('visibility', '');
    }

    public function __invoke()
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'visibility' => $this->visibility,
        ];
    }
}