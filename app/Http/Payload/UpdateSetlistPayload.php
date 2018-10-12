<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class UpdateSetlistPayload
{
    private $uuid;
    private $name;
    private $acts;
    private $date;

    public function __construct(Request $request)
    {
        $this->uuid = $request->route()[2]['id'];
        $this->name = $request->input('name', '');
        $this->acts = $request->input('acts', []);
        $this->date = $request->input('date', '');
    }

    public function __invoke()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'acts' => $this->acts,
            'date' => $this->date,
        ];
    }
}