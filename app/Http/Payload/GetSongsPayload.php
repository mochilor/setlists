<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class GetSongsPayload
{
    private $start;
    private $length;
    private $title;
    private $notIn;

    public function __construct(Request $request)
    {
        $parameters = $request->toArray();
        if (isset($parameters['interval']) && preg_match('/^\d+,\d+$/', $parameters['interval'])) {
            $this->start = explode(',', $parameters['interval'])[0];
            $this->length = explode(',', $parameters['interval'])[1];
        }
        $this->title = $parameters['title'] ?? '';
        $this->notIn = $parameters['not-in'] ?? '';
    }

    public function __invoke()
    {
        return [
            'start' => (int) $this->start,
            'length' => (int) $this->length,
            'title' => (string) $this->title,
            'notIn' => (string) $this->notIn,
        ];
    }
}
