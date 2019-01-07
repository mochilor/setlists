<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class GetSetlistsPayload
{
    private $uuid;
    private $start;
    private $length;
    private $name;

    public function __construct(Request $request)
    {
        $idParameter = $request->route()[2];
        $this->uuid = $idParameter['id'] ?? '';

        $parameters = $request->toArray();
        if (isset($parameters['interval']) && preg_match('/^\d+,\d+$/', $parameters['interval'])) {
            $this->start = explode(',', $parameters['interval'])[0];
            $this->length = explode(',', $parameters['interval'])[1];
        }
        $this->name = $parameters['name'] ?? '';
    }

    public function __invoke()
    {
        return [
            'uuid' => $this->uuid,
            'start' => (int) $this->start,
            'length' => (int) $this->length,
            'name' => (string) $this->name,
        ];
    }
}
