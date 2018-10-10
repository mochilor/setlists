<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class CreateSetlistPayload
{
    private $name;
    private $acts;
    private $date;

    public function __construct(Request $request)
    {
        $this->name = $request->input('name', '');
        $this->acts = $request->input('acts', []);
        $this->date = $request->input('date', '');
    }

    public function __invoke()
    {
        return [
            'name' => $this->name,
            'acts' => $this->acts,
            'date' => $this->date,
        ];
    }
}