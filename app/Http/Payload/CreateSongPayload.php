<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class CreateSongPayload
{
    private $title;

    public function __construct(Request $request)
    {
        $this->title = $request->input('title', '');
    }

    public function __invoke()
    {
        return [
            'title' => $this->title,
        ];
    }
}