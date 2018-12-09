<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class CreateSetlistPayload
{
    private $name;
    private $description;
    private $acts;
    private $date;

    public function __construct(Request $request)
    {
        $this->name = $request->input('name', '');
        $this->description = $request->input('description', '');
        $this->acts = $request->input('acts', []);
        $this->date = $request->input('date', '');
    }

    public function __invoke()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'acts' => $this->acts,
            'date' => $this->date,
        ];
    }
}