<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class GetSongsPayload
{
    private $start;
    private $length;

    public function __construct(Request $request)
    {
        $parameters = $request->toArray();
        if (isset($parameters['interval']) && preg_match('/^\d+,\d+$/', $parameters['interval'])) {
            $this->start = explode(',', $parameters['interval'])[0];
            $this->length = explode(',', $parameters['interval'])[1];
        }
    }

    public function __invoke()
    {
        return [
            'start' => (int) $this->start,
            'length' => (int) $this->length,
        ];
    }
}
