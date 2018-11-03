<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class GetSongsByTitlePayload
{
    private $title;

    public function __construct(Request $request)
    {
        $titleParameter = $request->route()[2];
        $this->title = $titleParameter['title'];
    }

    public function __invoke()
    {
        return [
            'title' => $this->title,
        ];
    }
}
