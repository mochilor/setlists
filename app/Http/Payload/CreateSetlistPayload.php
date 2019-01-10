<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class CreateSetlistPayload
{
    private $uuid;
    private $name;
    private $description;
    private $acts;
    private $date;
    private $actsCleaner;

    public function __construct(Request $request, ActsCleaner $actsCleaner)
    {
        $this->uuid = $request->input('id', '');
        $this->name = $request->input('name', '');
        $this->description = $request->input('description', '');
        $this->acts = $actsCleaner->cleanActs($request->input('acts', []));
        $this->date = $request->input('date', '');
    }

    public function __invoke()
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'acts' => $this->acts,
            'date' => $this->date,
        ];
    }
}