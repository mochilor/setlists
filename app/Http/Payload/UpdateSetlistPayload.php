<?php

namespace App\Http\Payload;

use Illuminate\Http\Request;

class UpdateSetlistPayload
{
    private $uuid;
    private $name;
    private $description;
    private $acts;
    private $date;

    public function __construct(Request $request, ActsCleaner $actsCleaner)
    {
        $this->uuid = $request->route()[2]['id'];
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